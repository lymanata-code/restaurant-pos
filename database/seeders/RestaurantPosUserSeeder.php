<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RestaurantPosUserSeeder extends AbstractRestaurantPosSeeder
{
    protected function seed(): void
    {
        $mainBranch = $this->branch('MAIN');

        foreach ([
            ['restaurant_id' => null, 'slug' => 'super-admin', 'name' => 'អភិបាលប្រព័ន្ធ', 'description' => 'អាចចូលប្រើគ្រប់មុខងារ', 'is_system' => true],
            ['restaurant_id' => null, 'slug' => 'manager', 'name' => 'អ្នកគ្រប់គ្រងសាខា', 'description' => 'គ្រប់គ្រងម៉ឺនុយ ស្តុក ការទិញ និងរបាយការណ៍', 'is_system' => true],
            ['restaurant_id' => null, 'slug' => 'cashier', 'name' => 'អ្នកគិតលុយ', 'description' => 'បង្កើតបញ្ជាទិញ ទទួលប្រាក់ និងគ្រប់គ្រងវេន', 'is_system' => true],
            ['restaurant_id' => null, 'slug' => 'accountant', 'name' => 'គណនេយ្យ', 'description' => 'គ្រប់គ្រងចំណូល ចំណាយ និងហិរញ្ញវត្ថុ', 'is_system' => true],
            ['restaurant_id' => null, 'slug' => 'waiter-server', 'name' => 'អ្នកបម្រើ', 'description' => 'ទទួលបញ្ជាទិញ និងបម្រើអតិថិជន', 'is_system' => true],
            ['restaurant_id' => null, 'slug' => 'kitchen-staff', 'name' => 'ផ្នែកផ្ទះបាយ', 'description' => 'មើលជួរផ្ទះបាយ និងធ្វើបច្ចុប្បន្នភាព KDS', 'is_system' => true],
        ] as $role) {
            $this->row('roles', ['restaurant_id' => $role['restaurant_id'], 'slug' => $role['slug']], $role);
        }

        foreach ([
            ['module' => 'dashboard', 'name' => 'មើលផ្ទាំងគ្រប់គ្រង', 'slug' => 'dashboard.view', 'description' => 'អាចមើលសូចនាករចម្បង'],
            ['module' => 'menu', 'name' => 'គ្រប់គ្រងម៉ឺនុយ', 'slug' => 'menu.manage', 'description' => 'បន្ថែម កែប្រែ និងលុបម៉ឺនុយ'],
            ['module' => 'table', 'name' => 'គ្រប់គ្រងតុ', 'slug' => 'table.manage', 'description' => 'គ្រប់គ្រងតុ និងតំបន់'],
            ['module' => 'customer', 'name' => 'គ្រប់គ្រងអតិថិជន', 'slug' => 'customer.manage', 'description' => 'រក្សាទុកប្រវត្តិអតិថិជន'],
            ['module' => 'order', 'name' => 'គ្រប់គ្រងការបញ្ជាទិញ', 'slug' => 'order.manage', 'description' => 'បង្កើត និងកែប្រែការបញ្ជាទិញ'],
            ['module' => 'order', 'name' => 'លុបចោលការបញ្ជាទិញ', 'slug' => 'order.void', 'description' => 'សិទ្ធិលុបចោលការបញ្ជាទិញ'],
            ['module' => 'kitchen', 'name' => 'គ្រប់គ្រងផ្ទះបាយ', 'slug' => 'kitchen.manage', 'description' => 'ធ្វើបច្ចុប្បន្នភាពស្ថានភាពផ្ទះបាយ'],
            ['module' => 'payment', 'name' => 'ទទួលការទូទាត់', 'slug' => 'payment.receive', 'description' => 'អាចទទួលប្រាក់ពីអតិថិជន'],
            ['module' => 'payment', 'name' => 'បង្វិលការទូទាត់', 'slug' => 'payment.refund', 'description' => 'អាចបង្វិលប្រាក់'],
            ['module' => 'promotion', 'name' => 'គ្រប់គ្រងប្រូម៉ូសិន', 'slug' => 'promotion.manage', 'description' => 'គ្រប់គ្រងការបញ្ចុះតម្លៃ និងគូប៉ុង'],
            ['module' => 'inventory', 'name' => 'គ្រប់គ្រងស្តុក', 'slug' => 'inventory.manage', 'description' => 'គ្រប់គ្រងសម្ភារៈក្នុងឃ្លាំង'],
            ['module' => 'purchasing', 'name' => 'គ្រប់គ្រងការទិញ', 'slug' => 'purchasing.manage', 'description' => 'គ្រប់គ្រងអ្នកផ្គត់ផ្គង់ និងការទិញទំនិញ'],
            ['module' => 'staff', 'name' => 'គ្រប់គ្រងបុគ្គលិក', 'slug' => 'staff.manage', 'description' => 'គ្រប់គ្រងបុគ្គលិក និងគណនីអ្នកប្រើ'],
            ['module' => 'accounting', 'name' => 'គ្រប់គ្រងគណនេយ្យ', 'slug' => 'accounting.manage', 'description' => 'មើល និងកត់ត្រាប្រតិបត្តិការហិរញ្ញវត្ថុ'],
            ['module' => 'report', 'name' => 'មើលរបាយការណ៍', 'slug' => 'report.view', 'description' => 'អាចចូលមើលរបាយការណ៍'],
            ['module' => 'setting', 'name' => 'គ្រប់គ្រងការកំណត់', 'slug' => 'setting.manage', 'description' => 'កែប្រែការកំណត់ប្រព័ន្ធ'],
        ] as $permission) {
            $this->row('permissions', ['slug' => $permission['slug']], $permission);
        }

        $roles = [
            'super-admin' => $this->find('roles', ['restaurant_id' => null, 'slug' => 'super-admin']),
            'manager' => $this->find('roles', ['restaurant_id' => null, 'slug' => 'manager']),
            'cashier' => $this->find('roles', ['restaurant_id' => null, 'slug' => 'cashier']),
            'accountant' => $this->find('roles', ['restaurant_id' => null, 'slug' => 'accountant']),
            'waiter-server' => $this->find('roles', ['restaurant_id' => null, 'slug' => 'waiter-server']),
            'kitchen-staff' => $this->find('roles', ['restaurant_id' => null, 'slug' => 'kitchen-staff']),
        ];

        $permissions = DB::table('permissions')->pluck('id', 'slug');

        foreach ($permissions as $permissionId) {
            $this->row('role_permission', ['role_id' => $roles['super-admin']->id, 'permission_id' => $permissionId], []);
            $this->row('role_permission', ['role_id' => $roles['manager']->id, 'permission_id' => $permissionId], []);
        }

        foreach (['dashboard.view', 'order.manage', 'payment.receive', 'customer.manage', 'table.manage', 'menu.manage'] as $slug) {
            $this->row('role_permission', ['role_id' => $roles['cashier']->id, 'permission_id' => $permissions[$slug]], []);
        }

        foreach (['dashboard.view', 'order.manage', 'table.manage', 'customer.manage'] as $slug) {
            $this->row('role_permission', ['role_id' => $roles['waiter-server']->id, 'permission_id' => $permissions[$slug]], []);
        }

        foreach (['dashboard.view', 'kitchen.manage'] as $slug) {
            $this->row('role_permission', ['role_id' => $roles['kitchen-staff']->id, 'permission_id' => $permissions[$slug]], []);
        }

        foreach (['dashboard.view', 'payment.receive', 'payment.refund', 'accounting.manage', 'report.view'] as $slug) {
            $this->row('role_permission', ['role_id' => $roles['accountant']->id, 'permission_id' => $permissions[$slug]], []);
        }

        $managerStaff = $this->row('staff', ['staff_code' => 'STF-0001'], [
            'restaurant_id' => $mainBranch->id,
            'name' => 'សុខា ច័ន្ទរា',
            'phone' => '012 100 001',
            'email' => 'manager@restaurant.local',
            'address' => 'ខណ្ឌបឹងកេងកង ភ្នំពេញ',
            'position' => 'អ្នកគ្រប់គ្រងសាខា',
            'hire_date' => $this->now()->subYears(3)->toDateString(),
            'status' => 'active',
        ]);

        $cashierStaff = $this->row('staff', ['staff_code' => 'STF-0002'], [
            'restaurant_id' => $mainBranch->id,
            'name' => 'ស្រីពេជ្រ រតនា',
            'phone' => '012 100 002',
            'email' => 'cashier@restaurant.local',
            'position' => 'អ្នកគិតលុយ',
            'hire_date' => $this->now()->subYears(2)->toDateString(),
            'status' => 'active',
        ]);

        $waiterStaff = $this->row('staff', ['staff_code' => 'STF-0003'], [
            'restaurant_id' => $mainBranch->id,
            'name' => 'វិចិត្រ ដារ៉ា',
            'phone' => '012 100 003',
            'email' => 'waiter@restaurant.local',
            'position' => 'អ្នកបម្រើ',
            'hire_date' => $this->now()->subMonths(18)->toDateString(),
            'status' => 'active',
        ]);

        $kitchenStaff = $this->row('staff', ['staff_code' => 'STF-0004'], [
            'restaurant_id' => $mainBranch->id,
            'name' => 'ម៉ាលី សុភា',
            'phone' => '012 100 004',
            'email' => 'chef@restaurant.local',
            'position' => 'មេចុងភៅ',
            'hire_date' => $this->now()->subMonths(15)->toDateString(),
            'status' => 'active',
        ]);

        $driverStaff = $this->row('staff', ['staff_code' => 'STF-0005'], [
            'restaurant_id' => $mainBranch->id,
            'name' => 'កក្កដា វិរៈ',
            'phone' => '012 100 005',
            'email' => 'driver@restaurant.local',
            'position' => 'អ្នកដឹកជញ្ជូន',
            'hire_date' => $this->now()->subMonths(8)->toDateString(),
            'status' => 'active',
        ]);

        $adminUser = $this->row('users', ['username' => 'admin'], [
            'restaurant_id' => null,
            'staff_id' => null,
            'name' => 'អភិបាលប្រព័ន្ធ',
            'email' => 'admin@restaurant.local',
            'phone' => '+855 12 000 000',
            'email_verified_at' => $this->now(),
            'password' => Hash::make('admin@123'),
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'status' => 'active',
            'last_login_at' => $this->now()->subMinutes(30),
            'remember_token' => null,
        ]);

        $managerUser = $this->row('users', ['username' => 'manager.main'], [
            'restaurant_id' => $mainBranch->id,
            'staff_id' => $managerStaff->id,
            'name' => 'សុខា ច័ន្ទរា',
            'email' => 'manager@restaurant.local',
            'phone' => '012 100 001',
            'email_verified_at' => $this->now(),
            'password' => Hash::make('manager@123'),
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'status' => 'active',
            'last_login_at' => $this->now()->subHour(),
            'remember_token' => null,
        ]);

        $cashierUser = $this->row('users', ['username' => 'cashier.main'], [
            'restaurant_id' => $mainBranch->id,
            'staff_id' => $cashierStaff->id,
            'name' => 'ស្រីពេជ្រ រតនា',
            'email' => 'cashier@restaurant.local',
            'phone' => '012 100 002',
            'email_verified_at' => $this->now(),
            'password' => Hash::make('cashier@123'),
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'status' => 'active',
            'last_login_at' => $this->now()->subMinutes(45),
            'remember_token' => null,
        ]);

        $waiterUser = $this->row('users', ['username' => 'waiter.main'], [
            'restaurant_id' => $mainBranch->id,
            'staff_id' => $waiterStaff->id,
            'name' => 'វិចិត្រ ដារ៉ា',
            'email' => 'waiter@restaurant.local',
            'phone' => '012 100 003',
            'email_verified_at' => $this->now(),
            'password' => Hash::make('waiter@123'),
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'status' => 'active',
            'last_login_at' => $this->now()->subMinutes(20),
            'remember_token' => null,
        ]);

        $chefUser = $this->row('users', ['username' => 'chef.main'], [
            'restaurant_id' => $mainBranch->id,
            'staff_id' => $kitchenStaff->id,
            'name' => 'ម៉ាលី សុភា',
            'email' => 'chef@restaurant.local',
            'phone' => '012 100 004',
            'email_verified_at' => $this->now(),
            'password' => Hash::make('chef@123'),
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'status' => 'active',
            'last_login_at' => $this->now()->subMinutes(15),
            'remember_token' => null,
        ]);

        $driverUser = $this->row('users', ['username' => 'driver.main'], [
            'restaurant_id' => $mainBranch->id,
            'staff_id' => $driverStaff->id,
            'name' => 'កក្កដា វិរៈ',
            'email' => 'driver@restaurant.local',
            'phone' => '012 100 005',
            'email_verified_at' => $this->now(),
            'password' => Hash::make('driver@123'),
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'status' => 'active',
            'last_login_at' => $this->now()->subMinutes(10),
            'remember_token' => null,
        ]);

        foreach ([
            [$adminUser->id, $roles['super-admin']->id],
            [$managerUser->id, $roles['manager']->id],
            [$managerUser->id, $roles['accountant']->id],
            [$cashierUser->id, $roles['cashier']->id],
            [$waiterUser->id, $roles['waiter-server']->id],
            [$chefUser->id, $roles['kitchen-staff']->id],
        ] as [$userId, $roleId]) {
            $this->row('user_role', ['user_id' => $userId, 'role_id' => $roleId], []);
        }

        $this->row('login_histories', ['username' => 'admin', 'ip_address' => '127.0.0.1', 'success' => true], [
            'user_id' => $adminUser->id,
            'user_agent' => 'Seeder Browser',
            'failure_reason' => null,
            'logged_in_at' => $this->now()->subMinutes(30),
            'logged_out_at' => null,
        ]);
    }
}
