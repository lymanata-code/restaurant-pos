<?php

namespace Database\Seeders;

class RestaurantPosInventorySeeder extends AbstractRestaurantPosSeeder
{
    protected function seed(): void
    {
        $mainBranch = $this->branch('MAIN');
        $managerUser = $this->find('users', ['username' => 'manager.main']);
        $kg = $this->find('units', ['symbol' => 'kg']);
        $g = $this->find('units', ['symbol' => 'g']);

        $inventoryCategory = $this->row('inventory_categories', ['restaurant_id' => $mainBranch->id, 'name' => 'គ្រឿងបន្លែ'], [
            'description' => 'បន្លែ និងគ្រឿងផ្សំស្រស់',
            'is_active' => true,
        ]);

        $beverageInventory = $this->row('inventory_categories', ['restaurant_id' => $mainBranch->id, 'name' => 'គ្រឿងភេសជ្ជៈ'], [
            'description' => 'វត្ថុធាតុសម្រាប់ភេសជ្ជៈ',
            'is_active' => true,
        ]);

        $onion = $this->row('stock_items', ['item_code' => 'STK-001'], [
            'restaurant_id' => $mainBranch->id,
            'inventory_category_id' => $inventoryCategory->id,
            'unit_id' => $kg->id,
            'name' => 'ខ្ទឹមបារាំង',
            'purchase_price' => 1.20,
            'quantity_on_hand' => 25,
            'reorder_level' => 10,
            'reorder_quantity' => 20,
            'expiry_date' => $this->now()->addDays(7)->toDateString(),
            'is_ingredient' => true,
            'is_active' => true,
        ]);

        $coffeeBeans = $this->row('stock_items', ['item_code' => 'STK-002'], [
            'restaurant_id' => $mainBranch->id,
            'inventory_category_id' => $beverageInventory->id,
            'unit_id' => $g->id,
            'name' => 'គ្រាប់កាហ្វេ',
            'purchase_price' => 0.02,
            'quantity_on_hand' => 5000,
            'reorder_level' => 1500,
            'reorder_quantity' => 3000,
            'expiry_date' => $this->now()->addMonths(4)->toDateString(),
            'is_ingredient' => true,
            'is_active' => true,
        ]);

        $potato = $this->row('stock_items', ['item_code' => 'STK-003'], [
            'restaurant_id' => $mainBranch->id,
            'inventory_category_id' => $inventoryCategory->id,
            'unit_id' => $kg->id,
            'name' => 'ដំឡូង',
            'purchase_price' => 1.10,
            'quantity_on_hand' => 18,
            'reorder_level' => 8,
            'reorder_quantity' => 15,
            'expiry_date' => $this->now()->addDays(10)->toDateString(),
            'is_ingredient' => true,
            'is_active' => true,
        ]);

        $lokLak = $this->find('menu_items', ['item_code' => 'FOOD-001']);
        $icedCoffee = $this->find('menu_items', ['item_code' => 'DRINK-001']);
        $fries = $this->find('menu_items', ['item_code' => 'SIDE-001']);

        $this->row('recipes', ['menu_item_id' => $lokLak->id, 'stock_item_id' => $onion->id], [
            'unit_id' => $kg->id,
            'quantity_required' => 0.10,
            'wastage_percent' => 2,
        ]);

        $this->row('recipes', ['menu_item_id' => $icedCoffee->id, 'stock_item_id' => $coffeeBeans->id], [
            'unit_id' => $g->id,
            'quantity_required' => 25,
            'wastage_percent' => 1,
        ]);

        $this->row('recipes', ['menu_item_id' => $fries->id, 'stock_item_id' => $potato->id], [
            'unit_id' => $kg->id,
            'quantity_required' => 0.20,
            'wastage_percent' => 5,
        ]);

        $supplier = $this->row('suppliers', ['supplier_code' => 'SUP-0001'], [
            'restaurant_id' => $mainBranch->id,
            'name' => 'កសិដ្ឋានស្រស់កម្ពុជា',
            'contact_person' => 'វណ្ណា',
            'phone' => '012 333 444',
            'email' => 'sales@freshfarm.local',
            'address' => 'ដង្កោ ភ្នំពេញ',
            'outstanding_balance' => 18,
            'status' => 'active',
        ]);

        $purchaseOrder = $this->row('purchase_orders', ['po_no' => 'PO-20260511-0001'], [
            'restaurant_id' => $mainBranch->id,
            'supplier_id' => $supplier->id,
            'status' => 'received',
            'order_date' => $this->now()->subDays(2)->toDateString(),
            'expected_date' => $this->now()->subDay()->toDateString(),
            'subtotal' => 18,
            'discount_amount' => 0,
            'tax_amount' => 0,
            'total_amount' => 18,
            'created_by' => $managerUser->id,
            'approved_by' => $managerUser->id,
            'approved_at' => $this->now()->subDays(2),
            'notes' => 'បញ្ជាទិញបន្លែសម្រាប់ចុងសប្ដាហ៍',
        ]);

        $purchaseOrderItem = $this->row('purchase_order_items', ['purchase_order_id' => $purchaseOrder->id, 'stock_item_id' => $onion->id], [
            'unit_id' => $kg->id,
            'quantity' => 15,
            'received_quantity' => 15,
            'unit_price' => 1.20,
            'line_total' => 18,
        ]);

        $goodsReceive = $this->row('goods_receives', ['grn_no' => 'GRN-20260511-0001'], [
            'purchase_order_id' => $purchaseOrder->id,
            'supplier_id' => $supplier->id,
            'status' => 'received',
            'received_date' => $this->now()->subDay()->toDateString(),
            'total_amount' => 18,
            'received_by' => $managerUser->id,
            'notes' => 'បានពិនិត្យ និងទទួលទំនិញរួចរាល់។',
        ]);

        $this->row('goods_receive_items', ['goods_receive_id' => $goodsReceive->id, 'stock_item_id' => $onion->id], [
            'purchase_order_item_id' => $purchaseOrderItem->id,
            'unit_id' => $kg->id,
            'quantity_received' => 15,
            'unit_cost' => 1.20,
            'line_total' => 18,
            'expiry_date' => $this->now()->addDays(7)->toDateString(),
        ]);

        $this->row('stock_movements', [
            'stock_item_id' => $onion->id,
            'movement_type' => 'stock_in',
            'reference_type' => 'App\\Models\\GoodsReceive',
            'reference_id' => $goodsReceive->id,
        ], [
            'unit_id' => $kg->id,
            'quantity' => 15,
            'unit_cost' => 1.20,
            'total_cost' => 18,
            'balance_after' => 25,
            'reason' => 'ទទួលស្តុកពីអ្នកផ្គត់ផ្គង់',
            'created_by' => $managerUser->id,
        ]);

        $stockAdjustment = $this->row('stock_adjustments', ['adjustment_no' => 'ADJ-20260511-0001'], [
            'restaurant_id' => $mainBranch->id,
            'status' => 'approved',
            'reason' => 'កែតម្រូវស្តុកប្រចាំថ្ងៃ',
            'created_by' => $managerUser->id,
            'approved_by' => $managerUser->id,
            'approved_at' => $this->now()->subMinutes(40),
        ]);

        $this->row('stock_adjustment_items', ['stock_adjustment_id' => $stockAdjustment->id, 'stock_item_id' => $onion->id], [
            'unit_id' => $kg->id,
            'system_quantity' => 25,
            'counted_quantity' => 24.5,
            'difference_quantity' => -0.5,
            'notes' => 'ខូចខាតពេលរៀបចំម្ហូប',
        ]);

        $this->row('supplier_accounts', ['supplier_id' => $supplier->id, 'goods_receive_id' => $goodsReceive->id, 'entry_type' => 'credit'], [
            'purchase_order_id' => $purchaseOrder->id,
            'payment_id' => null,
            'amount' => 18,
            'balance_after' => 18,
            'due_date' => $this->now()->addDays(7)->toDateString(),
            'description' => 'ទទួលទំនិញដោយជំពាក់អ្នកផ្គត់ផ្គង់',
        ]);
    }
}
