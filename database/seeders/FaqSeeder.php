<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Hai kak, untuk pesan custom baju prosesnya gimana ya? 🤔',
                'answer' => 'Halo kak! 👋 Gampang banget kok. Kakak tinggal chat admin kita via WhatsApp atau langsung pilih fitur "Minta Penawaran" di katalog ya. Nanti tim kita akan bantu diskusi desain, bahan, dan harganya sampai deal! ✨',
                'order' => 1,
            ],
            [
                'question' => 'Ada minimal order (MOQ) gak kak buat pesan seragam?',
                'answer' => 'Untuk pesanan baju custom / seragam, minimal ordernya (MOQ) mulai dari 12 pcs aja kak per desainnya. Makin banyak pesannya, harganya juga bisa makin miring loh! 😉',
                'order' => 2,
            ],
            [
                'question' => 'Kira-kira proses pengerjaannya butuh waktu berapa lama?',
                'answer' => 'Normalnya proses produksi makan waktu sekitar 2-3 minggu ya kak setelah desain fix dan DP masuk. Tapi kalau kakak butuh lebih cepat (urgent), bisa banget didiskusikan sama admin kita dulu ya! 🚀',
                'order' => 3,
            ],
            [
                'question' => 'Kak, aku udah punya desain sendiri nih, bisa kan?',
                'answer' => 'Wah, bisa banget kak! 😍 Kakak bisa langsung kirim file desainnya (usahakan format PDF/CDR/AI ya kak biar hasilnya tajam). Nanti tim desain kita tinggal menyesuaikan dengan pola potongannya.',
                'order' => 4,
            ],
            [
                'question' => 'Bisa minta dikirimin sampel bahan bajunya dulu gak?',
                'answer' => 'Tentu bisa kak! Kami bisa kirimkan katalog bahan fisik (handfeel) ke alamat kakak supaya kakak bisa pegang langsung bahannya. Atau kakak juga bisa mampir langsung ke workshop kita! 🏠',
                'order' => 5,
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
