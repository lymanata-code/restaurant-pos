<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DiningTable;
use App\Models\InventoryCategory;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Permission;
use App\Models\Restaurant;
use App\Models\Role;
use App\Models\Staff;
use App\Models\StockItem;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $branch = Restaurant::firstOrCreate(
                ['code' => 'MAIN'],
                [
                    'name' => 'Main Branch',
                    'phone' => '+855 12 345 678',
                    'email' => 'main@restaurant.local',
                    'address' => 'Phnom Penh, Cambodia',
                    'tax_number' => 'TIN-001',
                    'is_active' => true,
                ],
            );

            $branch2 = Restaurant::firstOrCreate(
                ['code' => 'TKM'],
                [
                    'name' => 'Toul Kork Branch',
                    'phone' => '+855 12 999 888',
                    'email' => 'tkm@restaurant.local',
                    'address' => 'Toul Kork, Phnom Penh',
                    'is_active' => true,
                ],
            );

            // Roles + permissions are seeded by the migration. Wire mappings.
            $superAdmin = Role::where('slug', 'super-admin')->first();
            $manager    = Role::where('slug', 'manager')->first();
            $cashier    = Role::where('slug', 'cashier')->first();

            $allPerms = Permission::pluck('id')->all();
            $superAdmin?->permissions()->syncWithoutDetaching($allPerms);
            $manager?->permissions()->syncWithoutDetaching($allPerms);
            $cashier?->permissions()->syncWithoutDetaching(Permission::whereIn('module', [
                'dashboard', 'order', 'payment', 'kitchen', 'customer', 'menu', 'table',
            ])->pluck('id')->all());

            // Default super-admin user
            $admin = User::updateOrCreate(
                ['username' => 'admin'],
                [
                    'restaurant_id' => null, // super-admin spans all branches
                    'name' => 'Super Admin',
                    'email' => 'admin@restaurant.local',
                    'phone' => '+855 12 000 000',
                    'password' => Hash::make('admin@123'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ],
            );
            if ($superAdmin) $admin->roles()->syncWithoutDetaching([$superAdmin->id]);

            // Per-branch manager
            $mgr = User::updateOrCreate(
                ['username' => 'manager'],
                [
                    'restaurant_id' => $branch->id,
                    'name' => 'Branch Manager',
                    'email' => 'manager@restaurant.local',
                    'password' => Hash::make('manager@123'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ],
            );
            if ($manager) $mgr->roles()->syncWithoutDetaching([$manager->id]);

            // Skip global branch scope while seeding (super-admin context).
            session(['current_branch_id' => $branch->id]);

            // Sample menu data
            $cat1 = MenuCategory::firstOrCreate(
                ['restaurant_id' => $branch->id, 'name' => 'Main Course'],
                ['code' => 'MAIN', 'sort_order' => 1, 'is_active' => true],
            );
            $cat2 = MenuCategory::firstOrCreate(
                ['restaurant_id' => $branch->id, 'name' => 'Beverages'],
                ['code' => 'BEV', 'sort_order' => 2, 'is_active' => true],
            );

            MenuItem::firstOrCreate(
                ['item_code' => 'SP-001'],
                [
                    'restaurant_id' => $branch->id,
                    'category_id' => $cat1->id,
                    'name' => 'Spicy Beef Lok Lak',
                    'sale_price' => 6.50,
                    'cost_price' => 2.50,
                    'is_available' => true,
                    'status' => 'active',
                ],
            );
            MenuItem::firstOrCreate(
                ['item_code' => 'SP-002'],
                [
                    'restaurant_id' => $branch->id,
                    'category_id' => $cat1->id,
                    'name' => 'Fish Amok',
                    'sale_price' => 7.00,
                    'cost_price' => 3.00,
                    'is_available' => true,
                    'status' => 'active',
                ],
            );
            MenuItem::firstOrCreate(
                ['item_code' => 'BV-001'],
                [
                    'restaurant_id' => $branch->id,
                    'category_id' => $cat2->id,
                    'name' => 'Iced Coffee',
                    'sale_price' => 2.25,
                    'cost_price' => 0.40,
                    'is_available' => true,
                    'status' => 'active',
                ],
            );

            $zone = Zone::firstOrCreate(
                ['restaurant_id' => $branch->id, 'name' => 'Main Hall'],
                ['sort_order' => 1, 'is_active' => true],
            );
            DiningTable::firstOrCreate(
                ['table_code' => 'T01'],
                ['restaurant_id' => $branch->id, 'zone_id' => $zone->id, 'table_no' => 'Table 1', 'capacity' => 4, 'status' => 'available', 'is_active' => true],
            );
            DiningTable::firstOrCreate(
                ['table_code' => 'T02'],
                ['restaurant_id' => $branch->id, 'zone_id' => $zone->id, 'table_no' => 'Table 2', 'capacity' => 2, 'status' => 'available', 'is_active' => true],
            );

            Customer::firstOrCreate(
                ['customer_code' => 'C-0001'],
                ['restaurant_id' => $branch->id, 'name' => 'Walk-in Guest', 'status' => 'active'],
            );

            Staff::firstOrCreate(
                ['staff_code' => 'STF-0001'],
                [
                    'restaurant_id' => $branch->id,
                    'name' => 'Sokha Pich',
                    'phone' => '012 111 222',
                    'position' => 'Cashier',
                    'status' => 'active',
                    'hire_date' => now()->subYears(1)->toDateString(),
                ],
            );

            Supplier::firstOrCreate(
                ['supplier_code' => 'SUP-0001'],
                [
                    'restaurant_id' => $branch->id,
                    'name' => 'Cambodia Fresh Farm',
                    'phone' => '012 333 444',
                    'status' => 'active',
                ],
            );

            $invCat = InventoryCategory::firstOrCreate(
                ['restaurant_id' => $branch->id, 'name' => 'Vegetables'],
                ['is_active' => true],
            );
            $kg = Unit::where('symbol', 'kg')->first();
            if ($kg) {
                StockItem::firstOrCreate(
                    ['item_code' => 'STK-VEG-001'],
                    [
                        'restaurant_id' => $branch->id,
                        'inventory_category_id' => $invCat->id,
                        'unit_id' => $kg->id,
                        'name' => 'Onion',
                        'purchase_price' => 1.20,
                        'quantity_on_hand' => 25,
                        'reorder_level' => 10,
                        'reorder_quantity' => 20,
                        'is_ingredient' => true,
                        'is_active' => true,
                    ],
                );
            }

            session()->forget('current_branch_id');
        });
    }
}
