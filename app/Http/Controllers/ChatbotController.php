<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->message;

        // Fetch active FAQs for context
        $faqs = Faq::where('is_active', true)->get();
        $faqContext = "BERIKUT ADALAH DATA FAQ YANG HARUS KAMU JADIKAN REFERENSI UTAMA DALAM MENJAWAB:\n";
        foreach ($faqs as $faq) {
            $faqContext .= "Tanya: {$faq->question}\nJawab: {$faq->answer}\n\n";
        }

        // System Prompt
        $systemPrompt = "Kamu adalah Customer Service resmi untuk 'Interco', sebuah vendor sablon dan konveksi baju custom. 
Tugasmu adalah menjawab pertanyaan pelanggan dengan ramah, sopan, kasual, profesional, menggunakan sapaan 'Kak', dan jangan lupa gunakan emoji yang sesuai.

ATURAN KETAT:
1. KAMU HANYA BOLEH MENJAWAB PERTANYAAN SEPUTAR: layanan konveksi, sablon, baju custom, pemesanan, harga, bahan baju, waktu produksi, dan hal-hal yang berkaitan dengan pakaian.
2. Jika pengguna bertanya di luar topik tersebut (misalnya cuaca, politik, coding, kesehatan umum, membuat puisi, dll), TOLAK DENGAN SOPAN dan arahkan kembali ke topik pembuatan seragam/baju.
3. Jawablah secara ringkas dan informatif.
4. Jangan membuat-buat harga, spesifikasi, atau data kontak yang tidak ada di dalam FAQ. Jika kamu tidak tahu, bilang saja 'Untuk detail tersebut, kakak bisa langsung chat admin kami via WhatsApp ya Kak! 😊'.

$faqContext";

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            // Fallback if API key is not set
            return response()->json([
                'reply' => 'Maaf kak, sistem Chatbot AI sedang dalam tahap pemeliharaan (API Key belum diatur). Silakan hubungi admin kami via WhatsApp ya! 🙏'
            ]);
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key={$apiKey}", [
                'system_instruction' => [
                    'parts' => [
                        ['text' => $systemPrompt]
                    ]
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.4, // Keep it relatively deterministic to stick to facts
                    'maxOutputTokens' => 1500, // Increased to allow room for model internal thoughts (which count towards this limit)
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Maaf kak, bot sedang bingung. Bisa diulangi pertanyaannya? 😥';
                
                return response()->json([
                    'reply' => $reply
                ]);
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return response()->json([
                    'reply' => 'Waduh kak, sistem bot sedang gangguan sementara. Mohon hubungi admin langsung ya! 🙏'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Gemini Exception: ' . $e->getMessage());
            return response()->json([
                'reply' => 'Waduh kak, bot gagal merespons. Mohon hubungi admin langsung ya! 🙏'
            ], 500);
        }
    }
}
