<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_open_dashboard(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->seedAdminData($superAdmin);

        $response = $this->actingAs($superAdmin)->get(route('admin.super.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
    }

    public function test_super_admin_can_open_reports_page(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get(route('admin.super.reports'));

        $response->assertOk();
        $response->assertSee('Laporan');
    }

    public function test_super_admin_can_access_dashboard_without_forbidden_error(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get(route('admin.super.dashboard'));

        $response->assertDontSee('Unauthorized access');
    }

    public function test_regular_user_cannot_open_super_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.super.dashboard'));

        $response->assertForbidden();
    }

    public function test_regular_user_cannot_open_super_admin_reports(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get(route('admin.super.reports'));

        $response->assertForbidden();
    }

    private function seedAdminData(User $superAdmin): void
    {
        Product::create([
            'name' => 'Kaos Premium',
            'sku' => 'SUP-001',
            'description' => 'Produk dummy super admin.',
            'price' => 100000,
            'unit' => 'piece',
            'stock' => 10,
            'category' => 'Clothing',
            'specifications' => 'Cotton',
            'is_active' => true,
        ]);

        Transaction::create([
            'user_id' => $superAdmin->id,
            'total_amount' => 100000,
            'status' => 'paid',
            'notes' => 'Data dashboard super admin',
        ]);
    }
}
