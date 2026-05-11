<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RestaurantPosAllInOneSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RestaurantPosSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_the_expected_khmer_demo_graph(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $mainBranch = DB::table('restaurants')->where('code', 'MAIN')->first();
        $adminUser = DB::table('users')->where('username', 'admin')->first();
        $managerUser = DB::table('users')->where('username', 'manager.main')->first();
        $superAdminRole = DB::table('roles')->where('slug', 'super-admin')->first();
        $menuItem = DB::table('menu_items')->where('item_code', 'FOOD-001')->first();
        $category = DB::table('menu_categories')->where('id', $menuItem->category_id)->first();
        $order = DB::table('orders')->where('order_no', 'ORD-20260511-0001')->first();
        $invoice = DB::table('invoices')->where('invoice_no', 'INV-202605-0001')->first();
        $payment = DB::table('payments')->where('payment_no', 'PAY-20260511-0001')->first();
        $deliveryOrder = DB::table('delivery_orders')->first();
        $reportExport = DB::table('report_exports')->where('report_type', 'sales')->first();

        $this->assertNotNull($mainBranch);
        $this->assertSame('សាខាចម្បង បឹងកេងកង', $mainBranch->name);
        $this->assertSame('សូមស្វាគមន៍មកកាន់ Restaurant POS', $mainBranch->receipt_header);

        $this->assertNotNull($adminUser);
        $this->assertNotNull($superAdminRole);
        $this->assertDatabaseHas('user_role', [
            'user_id' => $adminUser->id,
            'role_id' => $superAdminRole->id,
        ]);

        $this->assertNotNull($managerUser);
        $this->assertSame('ម្ហូបសំខាន់', $category->name);
        $this->assertSame('ឡុកឡាក់សាច់គោ', $menuItem->name);

        $this->assertNotNull($order);
        $this->assertSame('paid', $order->status);
        $this->assertSame((string) $order->id, (string) $invoice->order_id);
        $this->assertSame((string) $invoice->id, (string) $payment->invoice_id);
        $this->assertSame((string) $managerUser->restaurant_id, (string) $order->restaurant_id);

        $this->assertNotNull($deliveryOrder);
        $this->assertSame('on_the_way', $deliveryOrder->status);
        $this->assertDatabaseHas('delivery_partners', [
            'id' => $deliveryOrder->delivery_partner_id,
            'name' => 'Nham24',
        ]);

        $this->assertNotNull($reportExport);
        $this->assertStringContainsString('"locale":"km"', $reportExport->filters);
        $this->assertStringContainsString('sales-', $reportExport->file_path);
    }

    public function test_it_can_rerun_without_duplicating_key_seed_records(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $this->assertSame(1, DB::table('restaurants')->where('code', 'MAIN')->count());
        $this->assertSame(1, DB::table('users')->where('username', 'manager.main')->count());
        $this->assertSame(1, DB::table('menu_items')->where('item_code', 'FOOD-001')->count());
        $this->assertSame(1, DB::table('orders')->where('order_no', 'ORD-20260511-0001')->count());
        $this->assertSame(1, DB::table('invoices')->where('invoice_no', 'INV-202605-0001')->count());
        $this->assertSame(1, DB::table('coupons')->where('code', 'KM-LUNCH5')->count());
        $this->assertSame(1, DB::table('purchase_orders')->where('po_no', 'PO-20260511-0001')->count());
        $this->assertSame(1, DB::table('backups')->where('backup_no', 'BKP-20260511-0001')->count());
    }

    public function test_dashboard_renders_seeded_khmer_branch_and_user_context_for_super_admin(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $adminUser = $this->userByUsername('admin');
        $mainBranchId = DB::table('restaurants')->where('code', 'MAIN')->value('id');

        $response = $this->withSession(['current_branch_id' => $mainBranchId])
            ->actingAs($adminUser)
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSeeText('អភិបាលប្រព័ន្ធ');
        $response->assertSeeText('សាខាចម្បង បឹងកេងកង (MAIN)');
        $response->assertSeeText('សាខាទួលគោក (TKK)');
    }

    public function test_dashboard_pins_manager_to_their_assigned_branch(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $managerUser = $this->userByUsername('manager.main');

        $response = $this->actingAs($managerUser)
            ->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSeeText('សុខា ច័ន្ទរា');
        $response->assertSeeText('សាខាចម្បង បឹងកេងកង (MAIN)');
        $response->assertDontSeeText('សាខាទួលគោក (TKK)');
    }

    public function test_menu_listing_json_exposes_seeded_khmer_menu_records(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $managerUser = $this->userByUsername('manager.main');

        $response = $this->actingAs($managerUser)
            ->getJson(route('admin.menu-items.index'));

        $response->assertOk();

        $rows = collect($response->json('data'));

        $this->assertTrue($rows->contains(fn (array $row): bool =>
            ($row['item_code'] ?? null) === 'FOOD-001'
            && ($row['name'] ?? null) === 'ឡុកឡាក់សាច់គោ'
        ));

        $this->assertTrue($rows->contains(fn (array $row): bool =>
            ($row['item_code'] ?? null) === 'COMBO-001'
            && str_contains((string) ($row['name'] ?? ''), 'ឈុត')
        ));
    }

    public function test_orders_listing_json_exposes_seeded_dine_in_and_delivery_orders(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $managerUser = $this->userByUsername('manager.main');

        $response = $this->actingAs($managerUser)
            ->getJson(route('admin.orders.index'));

        $response->assertOk();

        $rows = collect($response->json('data'));

        $this->assertTrue($rows->contains(fn (array $row): bool =>
            ($row['order_no'] ?? null) === 'ORD-20260511-0001'
            && ($row['order_type'] ?? null) === 'dine_in'
            && (string) ($row['status'] ?? '') === 'paid'
        ));

        $this->assertTrue($rows->contains(fn (array $row): bool =>
            ($row['order_no'] ?? null) === 'ORD-20260511-0002'
            && ($row['order_type'] ?? null) === 'delivery'
            && (string) ($row['status'] ?? '') === 'ready'
        ));
    }

    public function test_notifications_listing_json_exposes_seeded_khmer_notification(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $managerUser = $this->userByUsername('manager.main');

        $response = $this->actingAs($managerUser)
            ->getJson(route('admin.notifications.index'));

        $response->assertOk();

        $rows = collect($response->json('data'));

        $this->assertTrue($rows->contains(fn (array $row): bool =>
            ($row['title'] ?? null) === 'ការដឹកជញ្ជូនបានចេញដំណើរ'
            && str_contains((string) ($row['message'] ?? ''), 'ORD-20260511-0002')
            && ($row['notification_type'] ?? null) === 'order_new'
        ));
    }

    public function test_audit_log_listing_json_exposes_seeded_order_creation_entry(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $managerUser = $this->userByUsername('manager.main');

        $response = $this->actingAs($managerUser)
            ->getJson(route('admin.audit-logs.index'));

        $response->assertOk();

        $rows = collect($response->json('data'));

        $this->assertTrue($rows->contains(fn (array $row): bool =>
            ($row['action'] ?? null) === 'create'
            && ($row['module'] ?? null) === 'order'
            && ($row['auditable_type'] ?? null) === 'App\\Models\\Order'
            && str_contains((string) ($row['reason'] ?? ''), 'POS')
        ));
    }

    public function test_super_admin_order_listing_respects_selected_branch_scope(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $adminUser = $this->userByUsername('admin');
        $tkkBranchId = (int) DB::table('restaurants')->where('code', 'TKK')->value('id');

        $response = $this->withSession(['current_branch_id' => $tkkBranchId])
            ->actingAs($adminUser)
            ->getJson(route('admin.orders.index'));

        $response->assertOk();

        $rows = collect($response->json('data'));

        $this->assertFalse($rows->contains(fn (array $row): bool =>
            in_array($row['order_no'] ?? null, ['ORD-20260511-0001', 'ORD-20260511-0002'], true)
        ));
    }

    public function test_locale_update_persists_supported_locale_in_session(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $adminUser = $this->userByUsername('admin');

        $response = $this->actingAs($adminUser)
            ->postJson(route('admin.locale.update'), ['locale' => 'km']);

        $response->assertOk()
            ->assertJson([
                'ok' => true,
                'locale' => 'km',
            ]);

        $this->assertSame('km', session('locale'));
    }

    public function test_locale_update_rejects_unsupported_locale(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $adminUser = $this->userByUsername('admin');

        $response = $this->withSession(['locale' => 'km'])
            ->actingAs($adminUser)
            ->postJson(route('admin.locale.update'), ['locale' => 'th']);

        $response->assertStatus(422)
            ->assertJson([
                'ok' => false,
                'message' => 'Unsupported locale.',
            ]);

        $this->assertSame('km', session('locale'));
    }

    public function test_super_admin_can_switch_active_branch_in_session(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $adminUser = $this->userByUsername('admin');
        $targetBranchId = (int) DB::table('restaurants')->where('code', 'TKK')->value('id');

        $response = $this->from(route('admin.dashboard'))
            ->actingAs($adminUser)
            ->post(route('admin.branches.switch'), ['restaurant_id' => $targetBranchId]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertSame($targetBranchId, session('current_branch_id'));
    }

    public function test_non_super_admin_cannot_switch_away_from_assigned_branch(): void
    {
        $this->seed(RestaurantPosAllInOneSeeder::class);

        $managerUser = $this->userByUsername('manager.main');
        $targetBranchId = (int) DB::table('restaurants')->where('code', 'TKK')->value('id');

        $response = $this->from(route('admin.dashboard'))
            ->withSession(['current_branch_id' => $managerUser->restaurant_id])
            ->actingAs($managerUser)
            ->post(route('admin.branches.switch'), ['restaurant_id' => $targetBranchId]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertSame($managerUser->restaurant_id, session('current_branch_id'));
    }

    private function userByUsername(string $username): User
    {
        return User::query()->where('username', $username)->firstOrFail();
    }
}
