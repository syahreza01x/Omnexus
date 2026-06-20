<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin User
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@omnexus.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08123456789',
            'role' => 'super_admin',
        ]);

        // Create Admin Web User
        User::create([
            'name' => 'Admin Web',
            'email' => 'adminweb@omnexus.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08234567890',
            'role' => 'admin_web',
        ]);

        // Create Admin Warehouse User
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admingudang@omnexus.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08345678901',
            'role' => 'admin_warehouse',
        ]);

        // Create Regular User for transactions
        $customer = User::create([
            'name' => 'Customer Testik',
            'email' => 'customer@omnexus.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '08456789012',
            'role' => 'user',
        ]);

        // Create Sample Products with various units
        $products = [
            [
                'name' => 'Kain Cotton Putih',
                'sku' => 'KAIN-001',
                'description' => 'Kain cotton premium berkualitas tinggi',
                'price' => 50000,
                'unit' => 'meter',
                'stock' => 100,
                'category' => 'Tekstil',
                'specifications' => 'Lebar: 150cm, Berat: 150gsm',
                'is_active' => true,
            ],
            [
                'name' => 'Kopi Arabika Premium',
                'sku' => 'KOPI-001',
                'description' => 'Kopi arabika pilihan dari dataran tinggi',
                'price' => 85000,
                'unit' => 'kg',
                'stock' => 45,
                'category' => 'Minuman',
                'specifications' => 'Roast: Medium, Origin: Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Plastik Kemasan',
                'sku' => 'PACK-001',
                'description' => 'Kantong plastik transparan berkualitas',
                'price' => 15000,
                'unit' => 'dozen',
                'stock' => 200,
                'category' => 'Kemasan',
                'specifications' => 'Ukuran: 30x40cm, Ketebalan: 0.08mm',
                'is_active' => true,
            ],
            [
                'name' => 'Minyak Kelapa Murni',
                'sku' => 'OIL-001',
                'description' => 'Minyak kelapa murni pilihan tanpa aditif',
                'price' => 120000,
                'unit' => 'liter',
                'stock' => 25,
                'category' => 'Minyak',
                'specifications' => 'Cold Pressed, % Kadar Lemak: 99%',
                'is_active' => true,
            ],
            [
                'name' => 'Telur Ayam Segar',
                'sku' => 'EGG-001',
                'description' => 'Telur ayam segar langsung dari peternakan',
                'price' => 28000,
                'unit' => 'box',
                'stock' => 30,
                'category' => 'Pertanian',
                'specifications' => 'Isi: 30 butir per box',
                'is_active' => true,
            ],
            [
                'name' => 'Laptop Stand Aluminium',
                'sku' => 'TECH-001',
                'description' => 'Stand laptop ergonomis dari aluminium',
                'price' => 350000,
                'unit' => 'piece',
                'stock' => 8,
                'category' => 'Elektronik',
                'specifications' => 'Max Load: 10kg, Adjustable Height',
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        // Create Sample Transactions
        $product1 = Product::query()->where('sku', '=', 'KAIN-001')->first();
        $product2 = Product::query()->where('sku', '=', 'KOPI-001')->first();
        $product3 = Product::query()->where('sku', '=', 'OIL-001')->first();

        // Transaction 1: Pending
        $transaction1 = Transaction::create([
            'user_id' => $customer->id,
            'total_amount' => ($product1->price * 5) + ($product2->price * 2),
            'status' => 'pending',
            'notes' => 'Pesanan untuk kebutuhan kantor',
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction1->id,
            'product_id' => $product1->id,
            'quantity' => 5,
            'unit_price' => $product1->price,
            'subtotal' => $product1->price * 5,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction1->id,
            'product_id' => $product2->id,
            'quantity' => 2,
            'unit_price' => $product2->price,
            'subtotal' => $product2->price * 2,
        ]);

        // Transaction 2: Paid
        $transaction2 = Transaction::create([
            'user_id' => $customer->id,
            'total_amount' => $product3->price * 3,
            'status' => 'paid',
            'paid_at' => now()->subDays(5),
            'notes' => 'Pesanan sudah lunas',
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction2->id,
            'product_id' => $product3->id,
            'quantity' => 3,
            'unit_price' => $product3->price,
            'subtotal' => $product3->price * 3,
        ]);

        // Transaction 3: Completed
        $transaction3 = Transaction::create([
            'user_id' => $customer->id,
            'total_amount' => ($product1->price * 2) + ($product3->price * 1),
            'status' => 'completed',
            'paid_at' => now()->subDays(10),
            'shipped_at' => now()->subDays(8),
            'completed_at' => now()->subDays(2),
            'notes' => 'Sudah sampai dan diterima',
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction3->id,
            'product_id' => $product1->id,
            'quantity' => 2,
            'unit_price' => $product1->price,
            'subtotal' => $product1->price * 2,
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction3->id,
            'product_id' => $product3->id,
            'quantity' => 1,
            'unit_price' => $product3->price,
            'subtotal' => $product3->price * 1,
        ]);
    }
}
