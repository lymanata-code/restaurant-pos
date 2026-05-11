<?php

namespace Database\Seeders;

class RestaurantPosCoreSeeder extends AbstractRestaurantPosSeeder
{
    protected function seed(): void
    {
        $mainBranch = $this->row('restaurants', ['code' => 'MAIN'], [
            'name' => 'សាខាចម្បង បឹងកេងកង',
            'logo_path' => 'branches/main/logo.svg',
            'address' => 'ផ្លូវ ៣១០ សង្កាត់បឹងកេងកង ភ្នំពេញ',
            'phone' => '+855 12 345 678',
            'email' => 'main@restaurant.local',
            'tax_number' => 'TIN-MAIN-001',
            'receipt_header' => 'សូមស្វាគមន៍មកកាន់ Restaurant POS',
            'receipt_footer' => 'សូមអរគុណ និងសង្ឃឹមថាបានជួបម្ដងទៀត។',
            'refund_policy' => 'អាចស្នើសុំបង្វិលប្រាក់ក្នុងរយៈពេល ២៤ ម៉ោង ដោយមានការអនុម័តពីអ្នកគ្រប់គ្រង។',
            'is_active' => true,
        ]);

        $this->row('restaurants', ['code' => 'TKK'], [
            'name' => 'សាខាទួលគោក',
            'address' => 'សង្កាត់ទួលគោក ភ្នំពេញ',
            'phone' => '+855 98 765 432',
            'email' => 'tkk@restaurant.local',
            'tax_number' => 'TIN-TKK-002',
            'is_active' => true,
        ]);

        foreach ([
            ['restaurant_id' => $mainBranch->id, 'group' => 'general', 'key' => 'currency', 'value' => 'USD', 'value_type' => 'string', 'description' => 'រូបិយប័ណ្ណសម្រាប់វិក្កយបត្រ', 'is_public' => true],
            ['restaurant_id' => $mainBranch->id, 'group' => 'general', 'key' => 'timezone', 'value' => 'Asia/Phnom_Penh', 'value_type' => 'string', 'description' => 'ម៉ោងប្រព័ន្ធសម្រាប់របាយការណ៍', 'is_public' => false],
            ['restaurant_id' => $mainBranch->id, 'group' => 'receipt', 'key' => 'show_tax_breakdown', 'value' => '1', 'value_type' => 'boolean', 'description' => 'បង្ហាញពន្ធលើបង្កាន់ដៃ', 'is_public' => false],
        ] as $setting) {
            $this->row('system_settings', [
                'restaurant_id' => $setting['restaurant_id'],
                'group' => $setting['group'],
                'key' => $setting['key'],
            ], $setting);
        }

        foreach ([
            ['restaurant_id' => $mainBranch->id, 'sequence_type' => 'customer', 'prefix' => 'CUS', 'date_format' => 'Ymd', 'next_number' => 2, 'padding' => 4, 'suffix' => null, 'reset_daily' => false],
            ['restaurant_id' => $mainBranch->id, 'sequence_type' => 'order', 'prefix' => 'ORD', 'date_format' => 'Ymd', 'next_number' => 3, 'padding' => 4, 'suffix' => null, 'reset_daily' => true],
            ['restaurant_id' => $mainBranch->id, 'sequence_type' => 'invoice', 'prefix' => 'INV', 'date_format' => 'Ym', 'next_number' => 2, 'padding' => 4, 'suffix' => null, 'reset_daily' => false],
            ['restaurant_id' => $mainBranch->id, 'sequence_type' => 'grn', 'prefix' => 'GRN', 'date_format' => 'Ym', 'next_number' => 2, 'padding' => 4, 'suffix' => null, 'reset_daily' => false],
        ] as $sequence) {
            $this->row('code_sequences', [
                'restaurant_id' => $sequence['restaurant_id'],
                'sequence_type' => $sequence['sequence_type'],
            ], $sequence);
        }

        foreach ([
            ['restaurant_id' => null, 'code' => 'CASH', 'name' => 'សាច់ប្រាក់', 'type' => 'cash', 'requires_reference' => false, 'is_online' => false, 'is_active' => true, 'gateway_config' => null],
            ['restaurant_id' => null, 'code' => 'ABA', 'name' => 'ABA', 'type' => 'aba', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'gateway_config' => $this->json(['provider' => 'aba'])],
            ['restaurant_id' => null, 'code' => 'KHQR', 'name' => 'KHQR', 'type' => 'khqr', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'gateway_config' => $this->json(['provider' => 'khqr'])],
            ['restaurant_id' => null, 'code' => 'WING', 'name' => 'Wing', 'type' => 'wing', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'gateway_config' => $this->json(['provider' => 'wing'])],
            ['restaurant_id' => null, 'code' => 'BANK_TRANSFER', 'name' => 'ផ្ទេរប្រាក់តាមធនាគារ', 'type' => 'bank_transfer', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'gateway_config' => $this->json(['provider' => 'bank'])],
            ['restaurant_id' => null, 'code' => 'CARD', 'name' => 'កាត', 'type' => 'card', 'requires_reference' => true, 'is_online' => false, 'is_active' => true, 'gateway_config' => null],
        ] as $paymentMethod) {
            $this->row('payment_methods', [
                'restaurant_id' => $paymentMethod['restaurant_id'],
                'code' => $paymentMethod['code'],
            ], $paymentMethod);
        }

        foreach ([
            ['name' => 'គីឡូក្រាម', 'symbol' => 'kg', 'unit_type' => 'weight', 'is_base' => true],
            ['name' => 'ក្រាម', 'symbol' => 'g', 'unit_type' => 'weight', 'is_base' => false],
            ['name' => 'លីត្រ', 'symbol' => 'l', 'unit_type' => 'volume', 'is_base' => true],
            ['name' => 'មីលីលីត្រ', 'symbol' => 'ml', 'unit_type' => 'volume', 'is_base' => false],
            ['name' => 'ដុំ', 'symbol' => 'pcs', 'unit_type' => 'piece', 'is_base' => true],
        ] as $unit) {
            $this->row('units', ['symbol' => $unit['symbol']], $unit);
        }

        $kg = $this->find('units', ['symbol' => 'kg']);
        $g = $this->find('units', ['symbol' => 'g']);

        $this->row('unit_conversions', ['from_unit_id' => $kg->id, 'to_unit_id' => $g->id], [
            'factor' => 1000,
        ]);

        $this->row('printers', ['restaurant_id' => $mainBranch->id, 'name' => 'ម៉ាស៊ីនបោះពុម្ពផ្ទះបាយ'], [
            'printer_type' => 'network',
            'ip_address' => '192.168.1.50',
            'port' => 9100,
            'paper_size' => '80mm',
            'is_default' => true,
            'is_active' => true,
        ]);

        $this->row('print_templates', ['restaurant_id' => $mainBranch->id, 'template_type' => 'receipt'], [
            'name' => 'គំរូបង្កាន់ដៃស្តង់ដារ',
            'content' => '[[branch_name]]\n[[order_no]]\n[[items]]\n[[totals]]',
            'settings' => $this->json(['font_size' => 'normal', 'locale' => 'km']),
            'is_default' => true,
        ]);
    }
}
