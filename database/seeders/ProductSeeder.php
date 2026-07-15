<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Seed product records for the homepage.
     */
    public function run(): void
    {
        // Hapus semua product lama
        Product::query()->delete();

        $products = [
            [
                'sku' => 'PRD-001',
                'name' => 'Kaos',
                'description' => 'Kaos polos custom dengan bahan cotton combed premium. Nyaman dipakai sehari-hari, cocok untuk seragam, merchandise, dan kebutuhan komunitas.',
                'price' => 95000,
                'image_path' => 'images/items/kaos.png',
                'unit' => 'piece',
                'stock' => 50,
                'category' => 'Kaos',
                'specifications' => 'Size S: PB 64, LB 47 | Size M: PB 67, LB 49 | Size L: PB 70, LB 51 | Size XL: PB 73, LB 53 | Size XXL: PB 76, LB 55',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-002',
                'name' => 'Polo Shirt',
                'description' => 'Polo shirt berkualitas tinggi dengan kerah dan kancing. Ideal untuk seragam kantor, event perusahaan, dan kebutuhan formal kasual.',
                'price' => 135000,
                'image_path' => 'images/items/polo-shirt.png',
                'unit' => 'piece',
                'stock' => 35,
                'category' => 'Polo',
                'specifications' => 'Size S: PB 64, LB 50 | Size M: PB 67, LB 52 | Size L: PB 70, LB 54 | Size XL: PB 73, LB 56 | Size XXL: PB 76, LB 68',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-003',
                'name' => 'Baju Lapangan',
                'description' => 'Baju lapangan tactical dengan bahan ripstop berkualitas. Dilengkapi saku fungsional, cocok untuk kegiatan outdoor, organisasi, dan dinas lapangan.',
                'price' => 185000,
                'image_path' => 'images/items/baju-lapangan.png',
                'unit' => 'piece',
                'stock' => 25,
                'category' => 'Seragam',
                'specifications' => 'Size S: PB 64, LB 50 | Size M: PB 67, LB 52 | Size L: PB 70, LB 54 | Size XL: PB 73, LB 56 | Size XXL: PB 76, LB 58',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-004',
                'name' => 'Jaket',
                'description' => 'Jaket parka outdoor dengan lapisan dalam bulu yang hangat. Tahan angin dan air, sempurna untuk cuaca dingin dan kegiatan luar ruangan.',
                'price' => 275000,
                'image_path' => 'images/items/jaket.png',
                'unit' => 'piece',
                'stock' => 20,
                'category' => 'Jaket',
                'specifications' => 'Size S: PB 64, LB 48 | Size M: PB 67, LB 51 | Size L: PB 70, LB 54 | Size XL: PB 73, LB 57 | Size XXL: PB 76, LB 60',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-005',
                'name' => 'Jaslab',
                'description' => 'Jas laboratorium putih dengan bahan anti-bakteri. Dirancang untuk profesional medis, laboratorium, farmasi, dan kebutuhan industri kesehatan.',
                'price' => 165000,
                'image_path' => 'images/items/jaslab.png',
                'unit' => 'piece',
                'stock' => 30,
                'category' => 'Seragam',
                'specifications' => 'Size S: PB 85, LB 55 | Size M: PB 90, LB 57 | Size L: PB 95, LB 59 | Size XL: PB 100, LB 61 | Size XXL: PB 105, LB 63',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-006',
                'name' => 'Wearpack',
                'description' => 'Wearpack (coverall) dengan bahan twill premium, tahan lama dan nyaman untuk kerja lapangan. Cocok untuk industri, bengkel, teknisi, dan kebutuhan safety.',
                'price' => 245000,
                'image_path' => 'images/items/wearpack.png',
                'unit' => 'piece',
                'stock' => 20,
                'category' => 'Seragam',
                'specifications' => 'Size S: PB 64, LB 50, PK 79 | Size M: PB 67, LB 52, PK 85 | Size L: PB 70, LB 54, PK 91 | Size XL: PB 73, LB 56, PK 97 | Size XXL: PB 76, LB 58, PK 103',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-007',
                'name' => 'Kaos Lapangan',
                'description' => 'Kaos lapangan lengan panjang dengan kerah polo. Bahan adem dan nyaman untuk aktivitas outdoor, cocok untuk seragam organisasi dan kegiatan lapangan.',
                'price' => 125000,
                'image_path' => 'images/items/kaos-lapangan.png',
                'unit' => 'piece',
                'stock' => 40,
                'category' => 'Kaos',
                'specifications' => 'Size S: PB 67, LB 53 | Size M: PB 70, LB 55 | Size L: PB 73, LB 57 | Size XL: PB 76, LB 59 | Size XXL: PB 79, LB 61',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-008',
                'name' => 'Gocarde + Namecard',
                'description' => 'Paket gocarde (lanyard) custom printing full color lengkap dengan namecard/ID card. Ideal untuk acara kampus, seminar, organisasi, dan event perusahaan.',
                'price' => 35000,
                'image_path' => 'images/items/gocarde-namecard.jpg',
                'unit' => 'piece',
                'stock' => 100,
                'category' => 'Aksesoris',
                'specifications' => 'Lanyard lebar 2cm, bahan polyester sublimasi. Namecard PVC ukuran standar 8.6 x 5.4 cm.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-009',
                'name' => 'Gocarde',
                'description' => 'Gocarde (lanyard) custom printing full color dengan bahan polyester sublimasi berkualitas tinggi. Desain bebas sesuai kebutuhan event dan organisasi.',
                'price' => 20000,
                'image_path' => 'images/items/gocarde.jpg',
                'unit' => 'piece',
                'stock' => 150,
                'category' => 'Aksesoris',
                'specifications' => 'Lanyard lebar 2cm, bahan polyester sublimasi. Dilengkapi hook dan safety buckle.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-010',
                'name' => 'Jersey',
                'description' => 'Jersey olahraga custom full print dengan bahan dry-fit yang ringan dan cepat kering. Desain bebas, cocok untuk tim futsal, sepakbola, dan komunitas olahraga.',
                'price' => 150000,
                'image_path' => 'images/items/jersey.png',
                'unit' => 'piece',
                'stock' => 30,
                'category' => 'Jersey',
                'specifications' => 'Bahan dry-fit polyester, printing sublimasi full color. Tersedia size S - XXL.',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}