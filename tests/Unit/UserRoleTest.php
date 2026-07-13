<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UserRoleTest extends TestCase
{
    #[DataProvider('adminRoleProvider')]
    public function test_is_admin_returns_true_for_admin_roles(string $role): void
    {
        $user = new User();
        $user->role = $role;

        $this->assertTrue($user->isAdmin());
    }

    public static function adminRoleProvider(): array
    {
        return [
            ['admin_web'],
            ['admin_warehouse'],
            ['super_admin'],
        ];
    }

    public function test_is_admin_returns_false_for_regular_user(): void
    {
        $user = new User();
        $user->role = 'user';

        $this->assertFalse($user->isAdmin());
    }

    public function test_is_super_admin_returns_true_only_for_super_admin(): void
    {
        $superAdmin = new User();
        $superAdmin->role = 'super_admin';

        $adminWeb = new User();
        $adminWeb->role = 'admin_web';

        $user = new User();
        $user->role = 'user';

        $this->assertTrue($superAdmin->isSuperAdmin());
        $this->assertFalse($adminWeb->isSuperAdmin());
        $this->assertFalse($user->isSuperAdmin());
    }
}
