<?php

namespace Database\Seeders;

class RestaurantPosOrderSeeder extends AbstractRestaurantPosSeeder
{
    protected function seed(): void
    {
        $mainBranch = $this->branch('MAIN');
        $managerUser = $this->find('users', ['username' => 'manager.main']);
        $cashierUser = $this->find('users', ['username' => 'cashier.main']);
        $waiterUser = $this->find('users', ['username' => 'waiter.main']);
        $chefUser = $this->find('users', ['username' => 'chef.main']);
        $driverUser = $this->find('users', ['username' => 'driver.main']);
        $table1 = $this->find('dining_tables', ['table_code' => 'T01']);
        $customer = $this->find('customers', ['customer_code' => 'CUS-0001']);
        $customerAddress = $this->find('customer_addresses', ['customer_id' => $customer->id, 'label' => 'ផ្ទះ']);
        $grillStation = $this->find('kitchen_stations', ['restaurant_id' => $mainBranch->id, 'name' => 'ស្ថានីយ៍ចម្អិនក្តៅ']);
        $drinkStation = $this->find('kitchen_stations', ['restaurant_id' => $mainBranch->id, 'name' => 'ស្ថានីយ៍ភេសជ្ជៈ']);
        $lokLak = $this->find('menu_items', ['item_code' => 'FOOD-001']);
        $icedCoffee = $this->find('menu_items', ['item_code' => 'DRINK-001']);
        $comboMeal = $this->find('menu_items', ['item_code' => 'COMBO-001']);
        $coffeeRegular = $this->find('menu_item_prices', ['menu_item_id' => $icedCoffee->id, 'size_name' => 'ធម្មតា']);
        $hotModifier = $this->find('modifiers', ['name' => 'ហឹរច្រើន']);
        $lessSugar = $this->find('modifiers', ['name' => 'ផ្អែមតិច']);
        $mainCourse = $this->find('menu_categories', ['restaurant_id' => $mainBranch->id, 'name' => 'ម្ហូបសំខាន់']);
        $cashMethod = $this->find('payment_methods', ['restaurant_id' => null, 'code' => 'CASH']);
        $supplier = $this->find('suppliers', ['supplier_code' => 'SUP-0001']);
        $purchaseOrder = $this->find('purchase_orders', ['po_no' => 'PO-20260511-0001']);
        $goodsReceive = $this->find('goods_receives', ['grn_no' => 'GRN-20260511-0001']);
        $coffeeBeans = $this->find('stock_items', ['item_code' => 'STK-002']);
        $g = $this->find('units', ['symbol' => 'g']);

        $lunchPromo = $this->row('promotions', ['restaurant_id' => $mainBranch->id, 'name' => 'បញ្ចុះតម្លៃម៉ោងថ្ងៃត្រង់ ៥%'], [
            'promotion_type' => 'bill_discount',
            'discount_type' => 'percent',
            'discount_value' => 5,
            'max_discount_amount' => 2,
            'min_spend' => 5,
            'start_time' => '11:00:00',
            'end_time' => '14:00:00',
            'start_date' => $this->now()->subDays(7)->toDateString(),
            'end_date' => $this->now()->addDays(30)->toDateString(),
            'days_of_week' => $this->json(['mon', 'tue', 'wed', 'thu', 'fri']),
            'requires_manager_approval' => false,
            'is_active' => true,
        ]);

        $this->row('promotion_targets', ['promotion_id' => $lunchPromo->id, 'target_type' => 'App\\Models\\MenuCategory', 'target_id' => $mainCourse->id], []);

        $coupon = $this->row('coupons', ['code' => 'KM-LUNCH5'], [
            'promotion_id' => $lunchPromo->id,
            'usage_limit' => 100,
            'used_count' => 1,
            'usage_limit_per_customer' => 1,
            'is_active' => true,
        ]);

        $dineInOrder = $this->row('orders', ['order_no' => 'ORD-20260511-0001'], [
            'restaurant_id' => $mainBranch->id,
            'order_type' => 'dine_in',
            'table_id' => $table1->id,
            'customer_id' => $customer->id,
            'customer_address_id' => null,
            'waiter_id' => $waiterUser->id,
            'cashier_id' => $cashierUser->id,
            'status' => 'paid',
            'subtotal' => 8.75,
            'discount_amount' => 0.44,
            'tax_amount' => 0.83,
            'service_charge_amount' => 0,
            'delivery_fee' => 0,
            'tip_amount' => 1.00,
            'total_amount' => 10.14,
            'paid_amount' => 10.14,
            'balance_amount' => 0,
            'is_split_bill' => false,
            'notes' => 'សូមដាក់ខ្ទឹមដោយឡែក',
            'sent_to_kitchen_at' => $this->now()->subHours(2),
            'served_at' => $this->now()->subHours(1)->subMinutes(40),
            'closed_at' => $this->now()->subHours(1)->subMinutes(20),
            'cancelled_by' => null,
            'cancel_reason' => null,
        ]);

        $deliverySourceOrder = $this->row('orders', ['order_no' => 'ORD-20260511-0002'], [
            'restaurant_id' => $mainBranch->id,
            'order_type' => 'delivery',
            'table_id' => null,
            'customer_id' => $customer->id,
            'customer_address_id' => $customerAddress->id,
            'waiter_id' => null,
            'cashier_id' => $cashierUser->id,
            'status' => 'ready',
            'subtotal' => 10.50,
            'discount_amount' => 0,
            'tax_amount' => 1.05,
            'service_charge_amount' => 0,
            'delivery_fee' => 1.50,
            'tip_amount' => 0,
            'total_amount' => 13.05,
            'paid_amount' => 0,
            'balance_amount' => 13.05,
            'is_split_bill' => false,
            'notes' => 'សូមទូរស័ព្ទមុនដល់',
            'sent_to_kitchen_at' => $this->now()->subMinutes(50),
            'served_at' => null,
            'closed_at' => null,
            'cancelled_by' => null,
            'cancel_reason' => null,
        ]);

        $lokLakOrderItem = $this->row('order_items', ['order_id' => $dineInOrder->id, 'item_name' => 'ឡុកឡាក់សាច់គោ'], [
            'menu_item_id' => $lokLak->id,
            'menu_item_price_id' => null,
            'kitchen_station_id' => $grillStation->id,
            'size_name' => null,
            'quantity' => 1,
            'unit_price' => 6.50,
            'unit_cost' => 2.70,
            'modifier_total' => 0,
            'discount_amount' => 0.32,
            'tax_amount' => 0.62,
            'line_total' => 6.80,
            'status' => 'served',
            'special_note' => 'បន្ថែមទឹកជ្រលក់',
            'sent_to_kitchen_at' => $this->now()->subHours(2),
            'ready_at' => $this->now()->subHours(1)->subMinutes(50),
            'served_at' => $this->now()->subHours(1)->subMinutes(40),
        ]);

        $coffeeOrderItem = $this->row('order_items', ['order_id' => $dineInOrder->id, 'item_name' => 'កាហ្វេទឹកកក'], [
            'menu_item_id' => $icedCoffee->id,
            'menu_item_price_id' => $coffeeRegular->id,
            'kitchen_station_id' => $drinkStation->id,
            'size_name' => 'ធម្មតា',
            'quantity' => 1,
            'unit_price' => 2.25,
            'unit_cost' => 0.60,
            'modifier_total' => 0,
            'discount_amount' => 0.12,
            'tax_amount' => 0.21,
            'line_total' => 2.34,
            'status' => 'served',
            'special_note' => 'ទឹកកកតិច',
            'sent_to_kitchen_at' => $this->now()->subHours(2),
            'ready_at' => $this->now()->subHours(1)->subMinutes(55),
            'served_at' => $this->now()->subHours(1)->subMinutes(40),
        ]);

        $deliveryOrderItem = $this->row('order_items', ['order_id' => $deliverySourceOrder->id, 'item_name' => 'ឈុតអាហារថ្ងៃត្រង់'], [
            'menu_item_id' => $comboMeal->id,
            'menu_item_price_id' => null,
            'kitchen_station_id' => $grillStation->id,
            'size_name' => null,
            'quantity' => 1,
            'unit_price' => 10.50,
            'unit_cost' => 4.50,
            'modifier_total' => 0,
            'discount_amount' => 0,
            'tax_amount' => 1.05,
            'line_total' => 11.55,
            'status' => 'ready',
            'special_note' => 'សូមដឹកឲ្យលឿន',
            'sent_to_kitchen_at' => $this->now()->subMinutes(50),
            'ready_at' => $this->now()->subMinutes(15),
            'served_at' => null,
        ]);

        $this->row('order_item_modifiers', ['order_item_id' => $lokLakOrderItem->id, 'modifier_name' => 'ហឹរច្រើន'], [
            'modifier_id' => $hotModifier->id,
            'modifier_group_name' => 'កម្រិតហឹរ',
            'extra_price' => 0,
            'extra_cost' => 0,
        ]);

        $this->row('order_item_modifiers', ['order_item_id' => $coffeeOrderItem->id, 'modifier_name' => 'ផ្អែមតិច'], [
            'modifier_id' => $lessSugar->id,
            'modifier_group_name' => 'កម្រិតផ្អែម',
            'extra_price' => 0,
            'extra_cost' => 0,
        ]);

        foreach ([
            ['order_id' => $dineInOrder->id, 'order_item_id' => null, 'from_status' => 'pending', 'to_status' => 'in_kitchen', 'changed_by' => $waiterUser->id, 'reason' => 'ផ្ញើទៅផ្ទះបាយ', 'changed_at' => $this->now()->subHours(2)],
            ['order_id' => $dineInOrder->id, 'order_item_id' => $lokLakOrderItem->id, 'from_status' => 'in_kitchen', 'to_status' => 'served', 'changed_by' => $chefUser->id, 'reason' => 'មុខម្ហូបរួចរាល់', 'changed_at' => $this->now()->subHours(1)->subMinutes(40)],
            ['order_id' => $deliverySourceOrder->id, 'order_item_id' => $deliveryOrderItem->id, 'from_status' => 'in_kitchen', 'to_status' => 'ready', 'changed_by' => $chefUser->id, 'reason' => 'ត្រៀមដឹកជញ្ជូន', 'changed_at' => $this->now()->subMinutes(15)],
        ] as $history) {
            $this->row('order_status_histories', [
                'order_id' => $history['order_id'],
                'order_item_id' => $history['order_item_id'],
                'to_status' => $history['to_status'],
                'reason' => $history['reason'],
            ], $history);
        }

        $kot = $this->row('kitchen_tickets', ['kot_no' => 'KOT-20260511-0001'], [
            'order_id' => $dineInOrder->id,
            'kitchen_station_id' => $grillStation->id,
            'status' => 'ready',
            'printed_at' => $this->now()->subHours(2),
            'ready_at' => $this->now()->subHours(1)->subMinutes(50),
        ]);

        $this->row('kitchen_ticket_items', ['kitchen_ticket_id' => $kot->id, 'order_item_id' => $lokLakOrderItem->id], []);

        $this->row('order_discounts', ['order_id' => $dineInOrder->id, 'discount_name' => 'បញ្ចុះតម្លៃម៉ោងថ្ងៃត្រង់ ៥%'], [
            'order_item_id' => null,
            'promotion_id' => $lunchPromo->id,
            'coupon_id' => $coupon->id,
            'discount_type' => 'percent',
            'discount_value' => 5,
            'discount_amount' => 0.44,
            'approved_by' => $managerUser->id,
            'applied_by' => $cashierUser->id,
            'reason' => 'អនុវត្តគូប៉ុងសម្រាប់ម៉ោងថ្ងៃត្រង់',
        ]);

        $invoice = $this->row('invoices', ['invoice_no' => 'INV-202605-0001'], [
            'restaurant_id' => $mainBranch->id,
            'order_id' => $dineInOrder->id,
            'receipt_no' => 'RCT-202605-0001',
            'invoice_type' => 'receipt',
            'status' => 'paid',
            'subtotal' => 8.75,
            'discount_amount' => 0.44,
            'tax_amount' => 0.83,
            'service_charge_amount' => 0,
            'tip_amount' => 1.00,
            'total_amount' => 10.14,
            'issued_at' => $this->now()->subHours(1)->subMinutes(30),
            'issued_by' => $cashierUser->id,
        ]);

        $cashShift = $this->row('cash_shifts', ['shift_no' => 'SHIFT-20260511-01'], [
            'restaurant_id' => $mainBranch->id,
            'cashier_id' => $cashierUser->id,
            'start_cash' => 100,
            'expected_cash' => 210.14,
            'counted_cash' => null,
            'short_over_amount' => 0,
            'status' => 'open',
            'opened_at' => $this->now()->subHours(9),
            'closed_at' => null,
            'closed_by' => null,
            'notes' => 'វេនព្រឹក',
        ]);

        $payment = $this->row('payments', ['payment_no' => 'PAY-20260511-0001'], [
            'restaurant_id' => $mainBranch->id,
            'order_id' => $dineInOrder->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'cash_shift_id' => $cashShift->id,
            'payment_method_id' => $cashMethod->id,
            'payment_type' => 'full',
            'amount' => 10.14,
            'received_amount' => 11.00,
            'change_amount' => 0.86,
            'reference_no' => null,
            'status' => 'success',
            'paid_by_user_id' => $cashierUser->id,
            'paid_at' => $this->now()->subHours(1)->subMinutes(25),
            'notes' => 'បង់ជាសាច់ប្រាក់',
        ]);

        $supplierPayment = $this->row('payments', ['payment_no' => 'PAY-20260511-0002'], [
            'restaurant_id' => $mainBranch->id,
            'order_id' => null,
            'invoice_id' => null,
            'customer_id' => null,
            'cash_shift_id' => $cashShift->id,
            'payment_method_id' => $cashMethod->id,
            'payment_type' => 'supplier_payment',
            'amount' => 18.00,
            'received_amount' => 18.00,
            'change_amount' => 0,
            'reference_no' => 'SUPP-SETTLEMENT-001',
            'status' => 'success',
            'paid_by_user_id' => $managerUser->id,
            'paid_at' => $this->now()->subMinutes(25),
            'notes' => 'ទូទាត់អ្នកផ្គត់ផ្គង់',
        ]);

        $this->row('cash_drawer_transactions', ['cash_shift_id' => $cashShift->id, 'transaction_type' => 'open_shift', 'reason' => 'បើកវេន'], [
            'payment_id' => null,
            'created_by' => $cashierUser->id,
            'amount' => 100,
        ]);

        $this->row('cash_drawer_transactions', ['cash_shift_id' => $cashShift->id, 'transaction_type' => 'payment', 'payment_id' => $payment->id], [
            'created_by' => $cashierUser->id,
            'amount' => 10.14,
            'reason' => 'ទទួលប្រាក់ពីអតិថិជន',
        ]);

        $this->row('refunds', ['refund_no' => 'REF-20260511-0001'], [
            'order_id' => $dineInOrder->id,
            'invoice_id' => $invoice->id,
            'payment_id' => $payment->id,
            'amount' => 1.00,
            'reason' => 'បង្វិលប្រាក់សម្រាប់សេវាកម្ម',
            'status' => 'approved',
            'requested_by' => $cashierUser->id,
            'approved_by' => $managerUser->id,
            'refunded_at' => $this->now()->subMinutes(50),
        ]);

        $this->row('customer_accounts', ['customer_id' => $customer->id, 'order_id' => $dineInOrder->id, 'entry_type' => 'debit'], [
            'payment_id' => null,
            'amount' => 10.14,
            'balance_after' => 10.14,
            'due_date' => $this->now()->toDateString(),
            'description' => 'បង្កើតវិក្កយបត្រសម្រាប់ការញ៉ាំនៅហាង',
        ]);

        $this->row('customer_accounts', ['customer_id' => $customer->id, 'payment_id' => $payment->id, 'entry_type' => 'credit'], [
            'order_id' => $dineInOrder->id,
            'amount' => 10.14,
            'balance_after' => 0,
            'due_date' => null,
            'description' => 'អតិថិជនបានទូទាត់ពេញ',
        ]);

        $this->row('supplier_accounts', ['supplier_id' => $supplier->id, 'payment_id' => $supplierPayment->id, 'entry_type' => 'debit'], [
            'purchase_order_id' => $purchaseOrder->id,
            'goods_receive_id' => $goodsReceive->id,
            'amount' => 18,
            'balance_after' => 0,
            'due_date' => null,
            'description' => 'ទូទាត់បំណុលអ្នកផ្គត់ផ្គង់រួចរាល់',
        ]);

        $this->row('stock_movements', [
            'stock_item_id' => $coffeeBeans->id,
            'movement_type' => 'sale_deduct',
            'reference_type' => 'App\\Models\\Order',
            'reference_id' => $dineInOrder->id,
        ], [
            'unit_id' => $g->id,
            'quantity' => 25,
            'unit_cost' => 0.02,
            'total_cost' => 0.50,
            'balance_after' => 4975,
            'reason' => 'កាត់ស្តុកសម្រាប់កាហ្វេទឹកកក',
            'created_by' => $cashierUser->id,
        ]);

        $this->row('qr_codes', ['code' => 'QR-T01'], [
            'restaurant_id' => $mainBranch->id,
            'table_id' => $table1->id,
            'url' => 'https://restaurant.local/order/table/T01',
            'is_active' => true,
            'expires_at' => $this->now()->addYear(),
        ]);

        $deliveryPartner = $this->row('delivery_partners', ['restaurant_id' => $mainBranch->id, 'name' => 'Nham24'], [
            'contact_phone' => '098 123 123',
            'integration_config' => $this->json(['api_mode' => 'sandbox', 'locale' => 'km']),
            'is_active' => true,
        ]);

        $this->row('delivery_orders', ['order_id' => $deliverySourceOrder->id], [
            'delivery_partner_id' => $deliveryPartner->id,
            'driver_user_id' => $driverUser->id,
            'status' => 'on_the_way',
            'delivery_fee' => 1.50,
            'tracking_no' => 'DLV-20260511-0001',
            'approved_at' => $this->now()->subMinutes(40),
            'picked_up_at' => $this->now()->subMinutes(20),
            'delivered_at' => null,
            'delivery_note' => 'សូមចូលតាមច្រកចំហៀងខុនដូ',
        ]);
    }
}
