<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // We use route middleware 'auth' to check authentication
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'category' => ['required', 'string', 'in:konveksi,merchandise'],
            'product' => ['required', 'string', 'max:100'],
            'other_product_name' => [
                'required_if:product,lainnya',
                'nullable',
                'string',
                'max:255'
            ],
            'quantity' => ['required', 'integer', 'min:1'],
            'color' => ['required', 'string', 'max:100'],
            'size' => [
                'required_if:category,konveksi',
                'nullable',
                'string',
                'max:50'
            ],
            'material_type' => ['required', 'string', 'max:100'],
            'production_technique' => ['required', 'string', 'in:Sablon,Bordir,Printing,DTF,UV Print,Lainnya'],
            'deadline' => ['required', 'date', 'after_or_equal:today'],
            'design_file' => [
                'required',
                'file',
                'max:10240', // 10MB limit
                function ($attribute, $value, $fail) {
                    if (!$value->isValid()) {
                        $fail('File desain tidak valid atau gagal diunggah.');
                        return;
                    }
                    $extension = strtolower($value->getClientOriginalExtension());
                    if (!in_array($extension, ['jpg', 'jpeg', 'png', 'pdf', 'ai', 'cdr'])) {
                        $fail('Format file desain harus berupa: JPG, JPEG, PNG, PDF, AI, atau CDR.');
                    }
                }
            ],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'Nama Pemesan',
            'whatsapp_number' => 'Nomor WhatsApp',
            'category' => 'Kategori Pesanan',
            'product' => 'Produk',
            'other_product_name' => 'Nama Produk Merchandise',
            'quantity' => 'Jumlah',
            'color' => 'Warna',
            'size' => 'Ukuran',
            'material_type' => 'Jenis Bahan',
            'production_technique' => 'Teknik Produksi',
            'deadline' => 'Deadline',
            'design_file' => 'File Desain',
            'notes' => 'Catatan Tambahan',
        ];
    }
}
