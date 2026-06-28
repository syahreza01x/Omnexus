<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CustomOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CustomOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test custom order creation validation and storage.
     */
    public function test_customer_can_create_custom_order(): void
    {
        Storage::fake('public');

        // Create a customer user
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->post(route('custom-orders.store'), [
            'customer_name' => 'Budi Saputra',
            'whatsapp_number' => '081234567890',
            'category' => 'konveksi',
            'product' => 'jersey',
            'quantity' => 10,
            'color' => 'Hitam',
            'size' => 'L',
            'material_type' => 'Drifit',
            'production_technique' => 'DTF',
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'design_file' => UploadedFile::fake()->create('desain.png', 100),
            'notes' => 'Catatan kaos sablon.',
        ]);

        $response->assertRedirect(route('custom-orders.index'));
        
        $this->assertDatabaseHas('custom_orders', [
            'user_id' => $user->id,
            'customer_name' => 'Budi Saputra',
            'whatsapp_number' => '081234567890',
            'category' => 'konveksi',
            'product' => 'jersey',
            'quantity' => 10,
            'status' => 'menunggu_review',
        ]);
    }

    /**
     * Test customer cannot see other customer's orders.
     */
    public function test_customer_cannot_view_others_orders(): void
    {
        $customerA = User::factory()->create(['role' => 'user']);
        $customerB = User::factory()->create(['role' => 'user']);

        $orderB = CustomOrder::create([
            'user_id' => $customerB->id,
            'customer_name' => 'Customer B',
            'whatsapp_number' => '08987654321',
            'category' => 'merchandise',
            'product' => 'Totebag',
            'quantity' => 50,
            'color' => 'Putih',
            'material_type' => 'Kanvas',
            'production_technique' => 'Sablon',
            'deadline' => now()->addDays(14)->format('Y-m-d'),
            'design_file' => 'design_files/mock.png',
            'status' => 'menunggu_review',
        ]);

        // Accessing B's order as A should be forbidden (403)
        $response = $this->actingAs($customerA)->get(route('custom-orders.show', $orderB));
        $response->assertStatus(403);
    }
}
