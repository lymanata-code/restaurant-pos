<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RestaurantPosAllInOneSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Route::getRoutes()->getByName('tests.access.role-manager')) {
            Route::middleware(['web', 'admin.auth', 'role:manager'])
                ->get('/_tests/access/role-manager', fn () => response()->json(['ok' => true]))
                ->name('tests.access.role-manager');
        }

        if (!Route::getRoutes()->getByName('tests.access.permission-payment')) {
            Route::middleware(['web', 'admin.auth', 'permission:payment.receive'])
                ->get('/_tests/access/permission-payment', fn () => response()->json(['ok' => true]))
                ->name('tests.access.permission-payment');
        }

        if (!Route::getRoutes()->getByName('tests.access.permission-inventory')) {
            Route::middleware(['web', 'admin.auth', 'permission:inventory.manage'])
                ->get('/_tests/access/permission-inventory', fn () => response()->json(['ok' => true]))
                ->name('tests.access.permission-inventory');
        }

        if (!Route::getRoutes()->getByName('tests.access.permission-settings')) {
            Route::middleware(['web', 'admin.auth', 'permission:setting.manage'])
                ->get('/_tests/access/permission-settings', fn () => response()->json(['ok' => true]))
                ->name('tests.access.permission-settings');
        }
    }

    public function test_role_middleware_allows_manager_and_blocks_cashier(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $this->actingAs($this->userByUsername('manager.main'))
            ->getJson('/_tests/access/role-manager')
            ->assertOk()
            ->assertJson(['ok' => true]);

        $this->actingAs($this->userByUsername('cashier.main'))
            ->getJson('/_tests/access/role-manager')
            ->assertForbidden();
    }

    public function test_permission_middleware_allows_cashier_to_receive_payments(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $this->actingAs($this->userByUsername('cashier.main'))
            ->getJson('/_tests/access/permission-payment')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    public function test_permission_middleware_blocks_cashier_from_inventory_management(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $this->actingAs($this->userByUsername('cashier.main'))
            ->getJson('/_tests/access/permission-inventory')
            ->assertForbidden();
    }

    public function test_permission_middleware_blocks_waiter_from_payment_receive(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $this->actingAs($this->userByUsername('waiter.main'))
            ->getJson('/_tests/access/permission-payment')
            ->assertForbidden();
    }

    public function test_super_admin_bypasses_specific_permission_checks(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $this->actingAs($this->userByUsername('admin'))
            ->getJson('/_tests/access/permission-settings')
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    private function userByUsername(string $username): User
    {
        return User::query()->where('username', $username)->firstOrFail();
    }
}
