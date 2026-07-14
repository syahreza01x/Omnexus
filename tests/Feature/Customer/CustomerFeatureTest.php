<?php

namespace Tests\Feature\Customer;

use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_see_active_products_on_homepage(): void
    {
        $product = Product::create([
            'name' => 'Kaos Basic',
            'sku' => 'CUST-001',
            'description' => 'Produk homepage.',
            'price' => 75000,
            'unit' => 'piece',
            'stock' => 12,
            'category' => 'Clothing',
            'specifications' => 'Cotton',
            'is_active' => true,
        ]);

        $response = $this->get(route('beranda'));

        $response->assertOk();
        $response->assertSee($product->name);
    }

    public function test_customer_can_open_profile_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertOk();
        $response->assertSee('Akun Saya');
    }

    public function test_customer_can_add_address(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('address.store'), [
            'label' => 'Rumah',
            'address_line' => 'Jl. Mawar No. 10',
            'city' => 'Padang',
            'province' => 'Sumatra Barat',
            'postal_code' => '25111',
            'phone' => '081234567890',
            'is_default' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'city' => 'Padang',
        ]);
    }

    public function test_customer_can_add_product_to_cart(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::create([
            'name' => 'Hoodie Premium',
            'sku' => 'CUST-002',
            'description' => 'Produk cart.',
            'price' => 120000,
            'unit' => 'piece',
            'stock' => 8,
            'category' => 'Clothing',
            'specifications' => 'Fleece',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertSessionHas('success', 'Produk ditambahkan ke keranjang.');
    }

    public function test_customer_can_open_checkout_page_when_cart_exists(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $product = Product::create([
            'name' => 'Kemeja Oversize',
            'sku' => 'CUST-003',
            'description' => 'Produk checkout.',
            'price' => 110000,
            'unit' => 'piece',
            'stock' => 8,
            'category' => 'Clothing',
            'specifications' => 'Linen',
            'is_active' => true,
        ]);

        Address::create([
            'user_id' => $user->id,
            'label' => 'Rumah',
            'address_line' => 'Jl. Contoh No. 1',
            'city' => 'Padang',
            'province' => 'Sumatra Barat',
            'postal_code' => '25111',
            'phone' => '081234567890',
            'is_default' => true,
        ]);

        $response = $this->actingAs($user)
            ->withSession([
                'cart' => [
                    (string) $product->id => [
                        'product_id' => $product->id,
                        'quantity' => 1,
                    ],
                ],
            ])
            ->get(route('checkout'));

        $response->assertOk();
        $response->assertSee('Checkout');
    }
}
