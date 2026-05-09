<?php
/**
 * One-off generator that creates a basic Eloquent model file for every
 * domain table in the migration. Run with: php scripts/generate_models.php
 */

require __DIR__ . '/../vendor/autoload.php';

$tables = [
    // System
    'restaurants' => ['class' => 'Restaurant', 'softDeletes' => true, 'hasRestaurant' => false,
        'fillable' => ['code','name','logo_path','address','phone','email','tax_number','receipt_header','receipt_footer','refund_policy','is_active'],
        'casts' => ['is_active' => 'boolean']],
    'system_settings' => ['class' => 'SystemSetting', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','group','key','value','value_type','description','is_public'],
        'casts' => ['is_public' => 'boolean']],
    'code_sequences' => ['class' => 'CodeSequence', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','sequence_type','prefix','date_format','next_number','padding','suffix','reset_daily'],
        'casts' => ['reset_daily' => 'boolean','next_number' => 'integer','padding' => 'integer']],
    'payment_methods' => ['class' => 'PaymentMethod', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','code','name','type','requires_reference','is_online','is_active','gateway_config'],
        'casts' => ['requires_reference' => 'boolean','is_online' => 'boolean','is_active' => 'boolean','gateway_config' => 'array']],
    'printers' => ['class' => 'Printer', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','printer_type','ip_address','port','paper_size','is_default','is_active'],
        'casts' => ['is_default' => 'boolean','is_active' => 'boolean']],
    'print_templates' => ['class' => 'PrintTemplate', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','template_type','name','content','settings','is_default'],
        'casts' => ['settings' => 'array','is_default' => 'boolean']],

    // Staff / RBAC
    'staff' => ['class' => 'Staff', 'table' => 'staff', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','staff_code','name','phone','email','address','position','hire_date','status'],
        'casts' => ['hire_date' => 'date']],
    'roles' => ['class' => 'Role', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','slug','description','is_system'],
        'casts' => ['is_system' => 'boolean']],
    'permissions' => ['class' => 'Permission', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['module','name','slug','description']],
    'login_histories' => ['class' => 'LoginHistory', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['user_id','username','ip_address','user_agent','success','failure_reason','logged_in_at','logged_out_at'],
        'casts' => ['success' => 'boolean','logged_in_at' => 'datetime','logged_out_at' => 'datetime']],

    // Menu
    'menu_categories' => ['class' => 'MenuCategory', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','parent_id','code','name','description','image_path','sort_order','is_active'],
        'casts' => ['is_active' => 'boolean']],
    'tax_rates' => ['class' => 'TaxRate', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','rate','type','is_inclusive','is_active'],
        'casts' => ['rate' => 'decimal:4','is_inclusive' => 'boolean','is_active' => 'boolean']],
    'menu_items' => ['class' => 'MenuItem', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','category_id','tax_rate_id','item_code','name','description','image_path','sale_price','cost_price','track_inventory','is_combo','is_available','status','sort_order'],
        'casts' => ['sale_price' => 'decimal:2','cost_price' => 'decimal:2','track_inventory' => 'boolean','is_combo' => 'boolean','is_available' => 'boolean']],
    'menu_item_prices' => ['class' => 'MenuItemPrice', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['menu_item_id','size_name','unit_name','price','cost_price','is_default','is_active'],
        'casts' => ['price' => 'decimal:2','cost_price' => 'decimal:2','is_default' => 'boolean','is_active' => 'boolean']],
    'modifier_groups' => ['class' => 'ModifierGroup', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','is_required','min_select','max_select','is_active'],
        'casts' => ['is_required' => 'boolean','is_active' => 'boolean']],
    'modifiers' => ['class' => 'Modifier', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['modifier_group_id','name','extra_price','extra_cost','sort_order','is_active'],
        'casts' => ['extra_price' => 'decimal:2','extra_cost' => 'decimal:2','is_active' => 'boolean']],
    'menu_item_modifier_group' => ['class' => 'MenuItemModifierGroup', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['menu_item_id','modifier_group_id']],
    'combo_items' => ['class' => 'ComboItem', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['combo_menu_item_id','child_menu_item_id','quantity','is_required'],
        'casts' => ['quantity' => 'decimal:3','is_required' => 'boolean']],

    // Tables / Customers
    'zones' => ['class' => 'Zone', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','description','sort_order','is_active'],
        'casts' => ['is_active' => 'boolean']],
    'dining_tables' => ['class' => 'DiningTable', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','zone_id','table_code','table_no','capacity','status','is_active'],
        'casts' => ['is_active' => 'boolean']],
    'customers' => ['class' => 'Customer', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','customer_code','name','phone','email','address','membership_no','points','credit_limit','outstanding_balance','is_blacklisted','notes','status'],
        'casts' => ['points' => 'integer','credit_limit' => 'decimal:2','outstanding_balance' => 'decimal:2','is_blacklisted' => 'boolean']],
    'customer_addresses' => ['class' => 'CustomerAddress', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['customer_id','label','contact_name','contact_phone','address','city','latitude','longitude','is_default'],
        'casts' => ['latitude' => 'decimal:7','longitude' => 'decimal:7','is_default' => 'boolean']],
    'table_moves' => ['class' => 'TableMove', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['from_table_id','to_table_id','moved_by','reason','moved_at'],
        'casts' => ['moved_at' => 'datetime']],

    // Kitchen
    'kitchen_stations' => ['class' => 'KitchenStation', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','printer_id','name','description','auto_print_kot','is_active'],
        'casts' => ['auto_print_kot' => 'boolean','is_active' => 'boolean']],
    'kitchen_station_menu_item' => ['class' => 'KitchenStationMenuItem', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['kitchen_station_id','menu_item_id']],

    // Orders
    'orders' => ['class' => 'Order', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','order_no','order_type','table_id','customer_id','customer_address_id','waiter_id','cashier_id','status','subtotal','discount_amount','tax_amount','service_charge_amount','delivery_fee','tip_amount','total_amount','paid_amount','balance_amount','is_split_bill','notes','sent_to_kitchen_at','served_at','closed_at','cancelled_by','cancel_reason'],
        'casts' => ['subtotal' => 'decimal:2','discount_amount' => 'decimal:2','tax_amount' => 'decimal:2','service_charge_amount' => 'decimal:2','delivery_fee' => 'decimal:2','tip_amount' => 'decimal:2','total_amount' => 'decimal:2','paid_amount' => 'decimal:2','balance_amount' => 'decimal:2','is_split_bill' => 'boolean','sent_to_kitchen_at' => 'datetime','served_at' => 'datetime','closed_at' => 'datetime']],
    'order_items' => ['class' => 'OrderItem', 'softDeletes' => true, 'hasRestaurant' => false,
        'fillable' => ['order_id','menu_item_id','menu_item_price_id','kitchen_station_id','item_name','size_name','quantity','unit_price','unit_cost','modifier_total','discount_amount','tax_amount','line_total','status','special_note','sent_to_kitchen_at','ready_at','served_at'],
        'casts' => ['quantity' => 'decimal:3','unit_price' => 'decimal:2','unit_cost' => 'decimal:2','modifier_total' => 'decimal:2','discount_amount' => 'decimal:2','tax_amount' => 'decimal:2','line_total' => 'decimal:2','sent_to_kitchen_at' => 'datetime','ready_at' => 'datetime','served_at' => 'datetime']],
    'order_item_modifiers' => ['class' => 'OrderItemModifier', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['order_item_id','modifier_id','modifier_group_name','modifier_name','extra_price','extra_cost'],
        'casts' => ['extra_price' => 'decimal:2','extra_cost' => 'decimal:2']],
    'order_status_histories' => ['class' => 'OrderStatusHistory', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['order_id','order_item_id','from_status','to_status','changed_by','reason','changed_at'],
        'casts' => ['changed_at' => 'datetime']],
    'kitchen_tickets' => ['class' => 'KitchenTicket', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['kot_no','order_id','kitchen_station_id','status','printed_at','ready_at'],
        'casts' => ['printed_at' => 'datetime','ready_at' => 'datetime']],
    'kitchen_ticket_items' => ['class' => 'KitchenTicketItem', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['kitchen_ticket_id','order_item_id']],

    // Promotions
    'promotions' => ['class' => 'Promotion', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','promotion_type','discount_type','discount_value','max_discount_amount','min_spend','start_time','end_time','start_date','end_date','days_of_week','requires_manager_approval','is_active'],
        'casts' => ['discount_value' => 'decimal:2','max_discount_amount' => 'decimal:2','min_spend' => 'decimal:2','start_date' => 'date','end_date' => 'date','days_of_week' => 'array','requires_manager_approval' => 'boolean','is_active' => 'boolean']],
    'promotion_targets' => ['class' => 'PromotionTarget', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['promotion_id','target_type','target_id']],
    'coupons' => ['class' => 'Coupon', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['promotion_id','code','usage_limit','used_count','usage_limit_per_customer','is_active'],
        'casts' => ['is_active' => 'boolean']],
    'order_discounts' => ['class' => 'OrderDiscount', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['order_id','order_item_id','promotion_id','coupon_id','discount_name','discount_type','discount_value','discount_amount','approved_by','applied_by','reason'],
        'casts' => ['discount_value' => 'decimal:2','discount_amount' => 'decimal:2']],

    // Billing/Payment
    'invoices' => ['class' => 'Invoice', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','order_id','invoice_no','receipt_no','invoice_type','status','subtotal','discount_amount','tax_amount','service_charge_amount','tip_amount','total_amount','issued_at','issued_by'],
        'casts' => ['subtotal' => 'decimal:2','discount_amount' => 'decimal:2','tax_amount' => 'decimal:2','service_charge_amount' => 'decimal:2','tip_amount' => 'decimal:2','total_amount' => 'decimal:2','issued_at' => 'datetime']],
    'cash_shifts' => ['class' => 'CashShift', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','cashier_id','shift_no','start_cash','expected_cash','counted_cash','short_over_amount','status','opened_at','closed_at','closed_by','notes'],
        'casts' => ['start_cash' => 'decimal:2','expected_cash' => 'decimal:2','counted_cash' => 'decimal:2','short_over_amount' => 'decimal:2','opened_at' => 'datetime','closed_at' => 'datetime']],
    'payments' => ['class' => 'Payment', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','order_id','invoice_id','customer_id','cash_shift_id','payment_method_id','payment_no','payment_type','amount','received_amount','change_amount','reference_no','status','paid_by_user_id','paid_at','notes'],
        'casts' => ['amount' => 'decimal:2','received_amount' => 'decimal:2','change_amount' => 'decimal:2','paid_at' => 'datetime']],
    'cash_drawer_transactions' => ['class' => 'CashDrawerTransaction', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['cash_shift_id','payment_id','created_by','transaction_type','amount','reason'],
        'casts' => ['amount' => 'decimal:2']],
    'refunds' => ['class' => 'Refund', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['order_id','invoice_id','payment_id','refund_no','amount','reason','status','requested_by','approved_by','refunded_at'],
        'casts' => ['amount' => 'decimal:2','refunded_at' => 'datetime']],
    'customer_accounts' => ['class' => 'CustomerAccount', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['customer_id','order_id','payment_id','entry_type','amount','balance_after','due_date','description'],
        'casts' => ['amount' => 'decimal:2','balance_after' => 'decimal:2','due_date' => 'date']],

    // Inventory
    'inventory_categories' => ['class' => 'InventoryCategory', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','description','is_active'],
        'casts' => ['is_active' => 'boolean']],
    'units' => ['class' => 'Unit', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['name','symbol','unit_type','is_base'],
        'casts' => ['is_base' => 'boolean']],
    'unit_conversions' => ['class' => 'UnitConversion', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['from_unit_id','to_unit_id','factor'],
        'casts' => ['factor' => 'decimal:6']],
    'stock_items' => ['class' => 'StockItem', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','inventory_category_id','unit_id','item_code','name','purchase_price','quantity_on_hand','reorder_level','reorder_quantity','expiry_date','is_ingredient','is_active'],
        'casts' => ['purchase_price' => 'decimal:2','quantity_on_hand' => 'decimal:4','reorder_level' => 'decimal:4','reorder_quantity' => 'decimal:4','expiry_date' => 'date','is_ingredient' => 'boolean','is_active' => 'boolean']],
    'recipes' => ['class' => 'Recipe', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['menu_item_id','stock_item_id','unit_id','quantity_required','wastage_percent'],
        'casts' => ['quantity_required' => 'decimal:4','wastage_percent' => 'decimal:4']],
    'stock_movements' => ['class' => 'StockMovement', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['stock_item_id','unit_id','reference_type','reference_id','movement_type','quantity','unit_cost','total_cost','balance_after','reason','created_by'],
        'casts' => ['quantity' => 'decimal:4','unit_cost' => 'decimal:2','total_cost' => 'decimal:2','balance_after' => 'decimal:4']],
    'stock_adjustments' => ['class' => 'StockAdjustment', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['adjustment_no','restaurant_id','status','reason','created_by','approved_by','approved_at'],
        'casts' => ['approved_at' => 'datetime']],
    'stock_adjustment_items' => ['class' => 'StockAdjustmentItem', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['stock_adjustment_id','stock_item_id','unit_id','system_quantity','counted_quantity','difference_quantity','notes'],
        'casts' => ['system_quantity' => 'decimal:4','counted_quantity' => 'decimal:4','difference_quantity' => 'decimal:4']],

    // Purchasing
    'suppliers' => ['class' => 'Supplier', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','supplier_code','name','contact_person','phone','email','address','outstanding_balance','status'],
        'casts' => ['outstanding_balance' => 'decimal:2']],
    'purchase_orders' => ['class' => 'PurchaseOrder', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','supplier_id','po_no','status','order_date','expected_date','subtotal','discount_amount','tax_amount','total_amount','created_by','approved_by','approved_at','notes'],
        'casts' => ['order_date' => 'date','expected_date' => 'date','subtotal' => 'decimal:2','discount_amount' => 'decimal:2','tax_amount' => 'decimal:2','total_amount' => 'decimal:2','approved_at' => 'datetime']],
    'purchase_order_items' => ['class' => 'PurchaseOrderItem', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['purchase_order_id','stock_item_id','unit_id','quantity','received_quantity','unit_price','line_total'],
        'casts' => ['quantity' => 'decimal:4','received_quantity' => 'decimal:4','unit_price' => 'decimal:2','line_total' => 'decimal:2']],
    'goods_receives' => ['class' => 'GoodsReceive', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['purchase_order_id','supplier_id','grn_no','status','received_date','total_amount','received_by','notes'],
        'casts' => ['received_date' => 'date','total_amount' => 'decimal:2']],
    'goods_receive_items' => ['class' => 'GoodsReceiveItem', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['goods_receive_id','purchase_order_item_id','stock_item_id','unit_id','quantity_received','unit_cost','line_total','expiry_date'],
        'casts' => ['quantity_received' => 'decimal:4','unit_cost' => 'decimal:2','line_total' => 'decimal:2','expiry_date' => 'date']],
    'supplier_accounts' => ['class' => 'SupplierAccount', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['supplier_id','purchase_order_id','goods_receive_id','payment_id','entry_type','amount','balance_after','due_date','description'],
        'casts' => ['amount' => 'decimal:2','balance_after' => 'decimal:2','due_date' => 'date']],

    // Accounting
    'accounts' => ['class' => 'Account', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['account_code','name','account_type','is_active'],
        'casts' => ['is_active' => 'boolean']],
    'expenses' => ['class' => 'Expense', 'softDeletes' => true, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','account_id','payment_method_id','expense_no','title','amount','expense_date','reference_no','description','created_by'],
        'casts' => ['amount' => 'decimal:2','expense_date' => 'date']],
    'journal_entries' => ['class' => 'JournalEntry', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','journal_no','journal_date','reference_type','reference_id','description','created_by'],
        'casts' => ['journal_date' => 'date']],
    'journal_entry_lines' => ['class' => 'JournalEntryLine', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['journal_entry_id','account_id','debit','credit','description'],
        'casts' => ['debit' => 'decimal:2','credit' => 'decimal:2']],

    // Online ordering
    'qr_codes' => ['class' => 'QrCode', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','table_id','code','url','is_active','expires_at'],
        'casts' => ['is_active' => 'boolean','expires_at' => 'datetime']],
    'delivery_partners' => ['class' => 'DeliveryPartner', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','name','contact_phone','integration_config','is_active'],
        'casts' => ['integration_config' => 'array','is_active' => 'boolean']],
    'delivery_orders' => ['class' => 'DeliveryOrder', 'softDeletes' => false, 'hasRestaurant' => false,
        'fillable' => ['order_id','delivery_partner_id','driver_user_id','status','delivery_fee','tracking_no','approved_at','picked_up_at','delivered_at','delivery_note'],
        'casts' => ['delivery_fee' => 'decimal:2','approved_at' => 'datetime','picked_up_at' => 'datetime','delivered_at' => 'datetime']],

    // System ops
    'notifications' => ['class' => 'Notification', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','user_id','title','message','notification_type','channel','reference_type','reference_id','read_at','sent_at'],
        'casts' => ['read_at' => 'datetime','sent_at' => 'datetime']],
    'report_exports' => ['class' => 'ReportExport', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','requested_by','report_type','format','filters','file_path','status','generated_at'],
        'casts' => ['filters' => 'array','generated_at' => 'datetime']],
    'audit_logs' => ['class' => 'AuditLog', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','user_id','module','action','auditable_type','auditable_id','old_values','new_values','reason','ip_address','user_agent','performed_at'],
        'casts' => ['old_values' => 'array','new_values' => 'array','performed_at' => 'datetime']],
    'file_uploads' => ['class' => 'FileUpload', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','uploaded_by','uploadable_type','uploadable_id','disk','path','original_name','mime_type','size_bytes']],
    'backups' => ['class' => 'Backup', 'softDeletes' => false, 'hasRestaurant' => true,
        'fillable' => ['restaurant_id','created_by','backup_no','backup_type','file_path','size_bytes','status','completed_at'],
        'casts' => ['completed_at' => 'datetime']],
];

$dir = __DIR__ . '/../app/Models';
@mkdir($dir, 0755, true);

// Models that already have hand-written, richer versions:
$skip = ['User','Restaurant','Role','Permission','MenuCategory','MenuItem','DiningTable','Zone','Staff','Order'];

foreach ($tables as $table => $cfg) {
    $class = $cfg['class'];
    if (in_array($class, $skip, true)) {
        continue;
    }
    $useSoft = $cfg['softDeletes'] ?? false;
    $useBranch = $cfg['hasRestaurant'] ?? false;
    $tableProp = $cfg['table'] ?? null;

    $usesList = ['use Illuminate\\Database\\Eloquent\\Model;'];
    $traits = [];

    if ($useSoft) {
        $usesList[] = 'use Illuminate\\Database\\Eloquent\\SoftDeletes;';
        $traits[] = 'SoftDeletes';
    }
    if ($useBranch) {
        $usesList[] = 'use App\\Models\\Concerns\\BelongsToBranch;';
        $traits[] = 'BelongsToBranch';
    }

    $usesStr = implode("\n", array_unique($usesList));
    $traitStr = $traits ? "    use " . implode(', ', $traits) . ";\n\n" : '';

    $tableLine = $tableProp ? "    protected \$table = '$tableProp';\n\n" : '';

    $fillable = $cfg['fillable'];
    $fillableLines = "    protected \$fillable = [\n" .
        implode("\n", array_map(fn ($f) => "        '$f',", $fillable)) . "\n    ];\n";

    $castsLines = '';
    if (!empty($cfg['casts'])) {
        $castsLines = "\n    protected \$casts = [\n" .
            implode("\n", array_map(fn ($k, $v) => "        '$k' => '$v',", array_keys($cfg['casts']), $cfg['casts'])) . "\n    ];\n";
    }

    $body = <<<PHP
<?php

namespace App\Models;

$usesStr

class $class extends Model
{
$traitStr$tableLine$fillableLines$castsLines}
PHP;

    file_put_contents("$dir/$class.php", $body);
    echo "wrote $class\n";
}
echo "done\n";
