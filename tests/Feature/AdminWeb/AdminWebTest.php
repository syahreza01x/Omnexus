<?php

namespace Tests\Feature\AdminWeb;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_web_can_open_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin_web']);
        $this->seedWebData($admin);

        $response = $this->actingAs($admin)->get(route('admin.web.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard Overview');
    }

    public function test_admin_web_can_open_products_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_web']);
        Product::create([
            'name' => 'Hoodie Basic',
            'sku' => 'WEB-001',
            'description' => 'Produk untuk halaman produk.',
            'price' => 120000,
            'unit' => 'piece',
            'stock' => 5,
            'category' => 'Clothing',
            'specifications' => 'Fleece',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.web.products'));

        $response->assertOk();
        $response->assertSee('Kelola Produk');
        $response->assertSee('Hoodie Basic');
    }

    public function test_admin_web_can_open_users_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_web']);

        $response = $this->actingAs($admin)->get(route('admin.web.users'));

        $response->assertOk();
        $response->assertSee('Daftar User');
    }

    public function test_admin_web_can_open_transactions_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin_web']);
        Transaction::create([
            'user_id' => $admin->id,
            'total_amount' => 150000,
            'status' => 'pending',
            'notes' => 'Order admin web',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.web.transactions'));

        $response->assertOk();
        $response->assertSee('Daftar Transaksi');
    }

    public function test_regular_user_cannot_open_admin_web_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.web.dashboard'));

        $response->assertForbidden();
    }

    private function seedWebData(User $admin): void
    {
        User::factory()->create(['role' => 'user']);
        Product::create([
            'name' => 'Kaos Basic',
            'sku' => 'WEB-002',
            'description' => 'Data dashboard admin web.',
            'price' => 75000,
            'unit' => 'piece',
            'stock' => 12,
            'category' => 'Clothing',
            'specifications' => 'Cotton',
            'is_active' => true,
        ]);
        Transaction::create([
            'user_id' => $admin->id,
            'total_amount' => 75000,
            'status' => 'paid',
            'notes' => 'Seed admin web',
        ]);
    }
}
