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
        $products = [
            [
                'sku' => 'PRD-001',
                'name' => 'Kemeja Flannel Custom',
                'description' => 'Bahan premium, desain bebas pilih',
                'price' => 185000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 24,
                'category' => 'Pakaian',
                'specifications' => 'Bisa custom warna dan bordir logo.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-002',
                'name' => 'Outer Hoodie Oversize',
                'description' => 'Fleece tebal, sablon & bordir',
                'price' => 220000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 18,
                'category' => 'Outer',
                'specifications' => 'Cocok untuk brand, komunitas, dan merchandise.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-003',
                'name' => 'Rompi Kerja Formal',
                'description' => 'Cutting presisi, bahan kantor',
                'price' => 165000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 31,
                'category' => 'Seragam',
                'specifications' => 'Material nyaman untuk kerja harian.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-004',
                'name' => 'Kaos Polos Custom',
                'description' => 'Cotton combed 30s, warna bebas',
                'price' => 95000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 45,
                'category' => 'Kaos',
                'specifications' => 'Tersedia sablon satu warna sampai full color.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-005',
                'name' => 'Jaket Varsity Custom',
                'description' => 'Kombinasi fleece & parasut',
                'price' => 275000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 12,
                'category' => 'Jaket',
                'specifications' => 'Desain lengan dan badan bisa dibedakan.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-006',
                'name' => 'Kemeja Batik Modern',
                'description' => 'Motif custom, slim fit',
                'price' => 210000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 21,
                'category' => 'Kemeja',
                'specifications' => 'Pilihan motif bisa disesuaikan acara.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-007',
                'name' => 'Topi Bucket Bordir',
                'description' => 'Bordir custom logo & teks',
                'price' => 75000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 60,
                'category' => 'Aksesoris',
                'specifications' => 'Bagus untuk souvenir dan merch event.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-008',
                'name' => 'Outer Parka Custom',
                'description' => 'Waterproof, desain bebas',
                'price' => 310000,
                'image_path' => 'images/items/1.png',
                'unit' => 'piece',
                'stock' => 9,
                'category' => 'Outer',
                'specifications' => 'Cocok untuk produk premium dan outdoor.',
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