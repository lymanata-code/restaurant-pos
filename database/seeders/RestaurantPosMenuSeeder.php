<?php

namespace Database\Seeders;

class RestaurantPosMenuSeeder extends AbstractRestaurantPosSeeder
{
    protected function seed(): void
    {
        $mainBranch = $this->branch('MAIN');
        $managerUser = $this->find('users', ['username' => 'manager.main']);
        $printer = $this->find('printers', ['restaurant_id' => $mainBranch->id, 'name' => 'ម៉ាស៊ីនបោះពុម្ពផ្ទះបាយ']);

        $mainCourse = $this->row('menu_categories', ['restaurant_id' => $mainBranch->id, 'name' => 'ម្ហូបសំខាន់'], [
            'parent_id' => null,
            'code' => 'MAIN',
            'description' => 'ម្ហូបខ្មែរ និងអាស៊ីដែលលក់ដាច់',
            'image_path' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $beverage = $this->row('menu_categories', ['restaurant_id' => $mainBranch->id, 'name' => 'ភេសជ្ជៈ'], [
            'parent_id' => null,
            'code' => 'BEV',
            'description' => 'ភេសជ្ជៈក្តៅ និងត្រជាក់',
            'image_path' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $comboCategory = $this->row('menu_categories', ['restaurant_id' => $mainBranch->id, 'name' => 'ឈុតពិសេស'], [
            'parent_id' => null,
            'code' => 'COMBO',
            'description' => 'ឈុតអាហាររួមសម្រាប់លក់លឿន',
            'image_path' => null,
            'sort_order' => 3,
            'is_active' => true,
        ]);

        $taxRate = $this->row('tax_rates', ['restaurant_id' => $mainBranch->id, 'name' => 'អាករ 10%'], [
            'rate' => 10,
            'type' => 'tax',
            'is_inclusive' => false,
            'is_active' => true,
        ]);

        $lokLak = $this->row('menu_items', ['item_code' => 'FOOD-001'], [
            'restaurant_id' => $mainBranch->id,
            'category_id' => $mainCourse->id,
            'tax_rate_id' => $taxRate->id,
            'name' => 'ឡុកឡាក់សាច់គោ',
            'description' => 'សាច់គោបំពងជាមួយម្រេចកំពត និងទឹកជ្រលក់ពិសេស។',
            'image_path' => 'menu/beef-lok-lak.jpg',
            'sale_price' => 6.50,
            'cost_price' => 2.70,
            'track_inventory' => true,
            'is_combo' => false,
            'is_available' => true,
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $icedCoffee = $this->row('menu_items', ['item_code' => 'DRINK-001'], [
            'restaurant_id' => $mainBranch->id,
            'category_id' => $beverage->id,
            'tax_rate_id' => $taxRate->id,
            'name' => 'កាហ្វេទឹកកក',
            'description' => 'កាហ្វេខ្មែរជាមួយទឹកដោះគោខាប់។',
            'image_path' => 'menu/iced-coffee.jpg',
            'sale_price' => 2.25,
            'cost_price' => 0.60,
            'track_inventory' => true,
            'is_combo' => false,
            'is_available' => true,
            'status' => 'active',
            'sort_order' => 2,
        ]);

        $fries = $this->row('menu_items', ['item_code' => 'SIDE-001'], [
            'restaurant_id' => $mainBranch->id,
            'category_id' => $mainCourse->id,
            'tax_rate_id' => $taxRate->id,
            'name' => 'ដំឡូងបំពង',
            'description' => 'ដំឡូងបំពងក្រឡុកស្រួយ សម្រាប់ជាម្ហូបបន្ថែម។',
            'image_path' => null,
            'sale_price' => 2.75,
            'cost_price' => 0.90,
            'track_inventory' => true,
            'is_combo' => false,
            'is_available' => true,
            'status' => 'active',
            'sort_order' => 3,
        ]);

        $comboMeal = $this->row('menu_items', ['item_code' => 'COMBO-001'], [
            'restaurant_id' => $mainBranch->id,
            'category_id' => $comboCategory->id,
            'tax_rate_id' => $taxRate->id,
            'name' => 'ឈុតអាហារថ្ងៃត្រង់',
            'description' => 'ឡុកឡាក់សាច់គោ ដំឡូងបំពង និងកាហ្វេទឹកកក។',
            'image_path' => null,
            'sale_price' => 10.50,
            'cost_price' => 4.50,
            'track_inventory' => false,
            'is_combo' => true,
            'is_available' => true,
            'status' => 'active',
            'sort_order' => 4,
        ]);

        $this->row('menu_item_prices', ['menu_item_id' => $icedCoffee->id, 'size_name' => 'ធម្មតា'], [
            'unit_name' => 'កែវ',
            'price' => 2.25,
            'cost_price' => 0.60,
            'is_default' => true,
            'is_active' => true,
        ]);

        $this->row('menu_item_prices', ['menu_item_id' => $icedCoffee->id, 'size_name' => 'ធំ'], [
            'unit_name' => 'កែវ',
            'price' => 2.75,
            'cost_price' => 0.70,
            'is_default' => false,
            'is_active' => true,
        ]);

        $spicyGroup = $this->row('modifier_groups', ['restaurant_id' => $mainBranch->id, 'name' => 'កម្រិតហឹរ'], [
            'is_required' => false,
            'min_select' => 0,
            'max_select' => 1,
            'is_active' => true,
        ]);

        $sugarGroup = $this->row('modifier_groups', ['restaurant_id' => $mainBranch->id, 'name' => 'កម្រិតផ្អែម'], [
            'is_required' => false,
            'min_select' => 0,
            'max_select' => 1,
            'is_active' => true,
        ]);

        $this->row('modifiers', ['modifier_group_id' => $spicyGroup->id, 'name' => 'ហឹរច្រើន'], [
            'extra_price' => 0,
            'extra_cost' => 0,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->row('modifiers', ['modifier_group_id' => $sugarGroup->id, 'name' => 'ផ្អែមធម្មតា'], [
            'extra_price' => 0,
            'extra_cost' => 0,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->row('modifiers', ['modifier_group_id' => $sugarGroup->id, 'name' => 'ផ្អែមតិច'], [
            'extra_price' => 0,
            'extra_cost' => 0,
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $this->row('menu_item_modifier_group', ['menu_item_id' => $lokLak->id, 'modifier_group_id' => $spicyGroup->id], []);
        $this->row('menu_item_modifier_group', ['menu_item_id' => $icedCoffee->id, 'modifier_group_id' => $sugarGroup->id], []);

        foreach ([$lokLak, $icedCoffee, $fries] as $childItem) {
            $this->row('combo_items', ['combo_menu_item_id' => $comboMeal->id, 'child_menu_item_id' => $childItem->id], [
                'quantity' => 1,
                'is_required' => true,
            ]);
        }

        $mainHall = $this->row('zones', ['restaurant_id' => $mainBranch->id, 'name' => 'សាលធំ'], [
            'description' => 'តំបន់អង្គុយសំខាន់សម្រាប់ភ្ញៀវ',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $patio = $this->row('zones', ['restaurant_id' => $mainBranch->id, 'name' => 'រានហាល'], [
            'description' => 'តំបន់អង្គុយខាងក្រៅ',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $table1 = $this->row('dining_tables', ['table_code' => 'T01'], [
            'restaurant_id' => $mainBranch->id,
            'zone_id' => $mainHall->id,
            'table_no' => 'តុ ១',
            'capacity' => 4,
            'status' => 'available',
            'is_active' => true,
        ]);

        $table2 = $this->row('dining_tables', ['table_code' => 'T02'], [
            'restaurant_id' => $mainBranch->id,
            'zone_id' => $patio->id,
            'table_no' => 'តុ ២',
            'capacity' => 2,
            'status' => 'reserved',
            'is_active' => true,
        ]);

        $customer = $this->row('customers', ['customer_code' => 'CUS-0001'], [
            'restaurant_id' => $mainBranch->id,
            'name' => 'ដារ៉ា សុខ',
            'phone' => '012 555 666',
            'email' => 'dara@example.com',
            'address' => 'បឹងកេងកង ភ្នំពេញ',
            'membership_no' => 'MEM-1001',
            'points' => 120,
            'credit_limit' => 150,
            'outstanding_balance' => 0,
            'is_blacklisted' => false,
            'notes' => 'អតិថិជនសមាជិក VIP',
            'status' => 'active',
        ]);

        $this->row('customer_addresses', ['customer_id' => $customer->id, 'label' => 'ផ្ទះ'], [
            'contact_name' => 'ដារ៉ា សុខ',
            'contact_phone' => '012 555 666',
            'address' => 'ផ្ទះលេខ ១០១ ផ្លូវ ៣១០',
            'city' => 'ភ្នំពេញ',
            'latitude' => 11.5564000,
            'longitude' => 104.9282000,
            'is_default' => true,
        ]);

        $this->row('table_moves', ['from_table_id' => $table1->id, 'to_table_id' => $table2->id, 'reason' => 'ភ្ញៀវស្នើអង្គុយនៅរានហាល'], [
            'moved_by' => $managerUser->id,
            'moved_at' => $this->now()->subHours(4),
        ]);

        $grillStation = $this->row('kitchen_stations', ['restaurant_id' => $mainBranch->id, 'name' => 'ស្ថានីយ៍ចម្អិនក្តៅ'], [
            'printer_id' => $printer->id,
            'description' => 'សម្រាប់ម្ហូបចម្អិនក្តៅ',
            'auto_print_kot' => true,
            'is_active' => true,
        ]);

        $drinkStation = $this->row('kitchen_stations', ['restaurant_id' => $mainBranch->id, 'name' => 'ស្ថានីយ៍ភេសជ្ជៈ'], [
            'printer_id' => $printer->id,
            'description' => 'សម្រាប់រៀបចំភេសជ្ជៈ',
            'auto_print_kot' => false,
            'is_active' => true,
        ]);

        $this->row('kitchen_station_menu_item', ['kitchen_station_id' => $grillStation->id, 'menu_item_id' => $lokLak->id], []);
        $this->row('kitchen_station_menu_item', ['kitchen_station_id' => $grillStation->id, 'menu_item_id' => $fries->id], []);
        $this->row('kitchen_station_menu_item', ['kitchen_station_id' => $drinkStation->id, 'menu_item_id' => $icedCoffee->id], []);
    }
}
