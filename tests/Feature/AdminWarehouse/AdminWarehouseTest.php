<?php

namespace Tests\Feature\AdminWarehouse;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminWarehouseTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_warehouse_can_open_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin_warehouse']);
        Product::create([
            'name' => 'Celana Jogger',
            'sku' => 'WH-001',
            'description' => 'Produk gudang.',
            'price' => 90000,
            'unit' => 'piece',
            'stock' => 8,
            'category' => 'Clothing',
            'specifications' => 'Polyester',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.warehouse.dashboard'));

        $response->assertOk();
        $response->assertSee('Stok');
    }

    public function test_admin_warehouse_can_open_stock_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin_warehouse']);
        Product::create([
            'name' => 'Hoodie Premium',
            'sku' => 'WH-002',
            'description' => 'Produk stok list.',
            'price' => 120000,
            'unit' => 'piece',
            'stock' => 6,
            'category' => 'Clothing',
            'specifications' => 'Fleece',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.warehouse.stocks'));

        $response->assertOk();
        $response->assertSee('Kelola Stok Barang');
    }

    public function test_admin_warehouse_can_filter_low_stock_products(): void
    {
        $admin = User::factory()->create(['role' => 'admin_warehouse']);
        Product::create([
            'name' => 'Kaos Thin',
            'sku' => 'WH-003',
            'description' => 'Stok rendah.',
            'price' => 50000,
            'unit' => 'piece',
            'stock' => 3,
            'category' => 'Clothing',
            'specifications' => 'Cotton',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.warehouse.stocks', ['low_stock' => 1]));

        $response->assertOk();
        $response->assertSee('Kaos Thin');
    }

    public function test_admin_warehouse_can_update_stock(): void
    {
        $admin = User::factory()->create(['role' => 'admin_warehouse']);
        $product = Product::create([
            'name' => 'Kemeja Oversize',
            'sku' => 'WH-004',
            'description' => 'Untuk update stok.',
            'price' => 110000,
            'unit' => 'piece',
            'stock' => 10,
            'category' => 'Clothing',
            'specifications' => 'Linen',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.warehouse.stocks.update', $product), [
            'quantity_change' => 5,
            'action' => 'added',
            'reason' => 'Stok masuk',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 15,
        ]);
    }

    public function test_regular_user_cannot_open_warehouse_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.warehouse.dashboard'));

        $response->assertForbidden();
    }
}
