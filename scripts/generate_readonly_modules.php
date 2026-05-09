<?php
$base = dirname(__DIR__);

$modules = [
    [
        'slug' => 'orders', 'resource' => 'Order', 'model' => 'Order',
        'columns' => [
            ['id','id','60px'],['order_no','order_no'],['order_type','order_type'],
            ['status','status','110px'],['total_amount','total_amount','120px'],
            ['created_at','created_at','160px'],
        ],
        'lang' => ['title' => 'Orders','order_no'=>'Order #','order_type'=>'Type','status'=>'Status','total_amount'=>'Total','created_at'=>'Created','id'=>'ID'],
        'lang_km' => ['title' => 'ការបញ្ជាទិញ','order_no'=>'លេខបញ្ជាទិញ','order_type'=>'ប្រភេទ','status'=>'ស្ថានភាព','total_amount'=>'សរុប','created_at'=>'បានបង្កើត','id'=>'លេខ'],
    ],
    [
        'slug' => 'kitchen-tickets', 'resource' => 'KitchenTicket', 'model' => 'KitchenTicket',
        'columns' => [
            ['id','id','60px'],['ticket_no','ticket_no'],['status','status','110px'],['issued_at','issued_at','160px'],
        ],
        'lang' => ['title' => 'Kitchen Tickets','ticket_no'=>'Ticket #','status'=>'Status','issued_at'=>'Issued At','id'=>'ID'],
        'lang_km' => ['title' => 'សំបុត្រផ្ទះបាយ','ticket_no'=>'លេខសំបុត្រ','status'=>'ស្ថានភាព','issued_at'=>'ចេញនៅ','id'=>'លេខ'],
    ],
    [
        'slug' => 'kitchen-stations', 'resource' => 'KitchenStation', 'model' => 'KitchenStation',
        'columns' => [
            ['id','id','60px'],['name','name'],['printer_id','printer_id','110px'],['is_active','is_active','110px'],
        ],
        'lang' => ['title' => 'Kitchen Stations','name'=>'Name','printer_id'=>'Printer','is_active'=>'Active','id'=>'ID'],
        'lang_km' => ['title' => 'ច្រកផ្ទះបាយ','name'=>'ឈ្មោះ','printer_id'=>'ម៉ាស៊ីនបោះពុម្ព','is_active'=>'សកម្ម','id'=>'លេខ'],
    ],
    [
        'slug' => 'login-histories', 'resource' => 'LoginHistory', 'model' => 'LoginHistory',
        'columns' => [
            ['id','id','60px'],['username','username'],['ip_address','ip_address'],['success','success','100px'],['logged_in_at','logged_in_at','160px'],
        ],
        'lang' => ['title' => 'Login History','username'=>'Username','ip_address'=>'IP','success'=>'Success','logged_in_at'=>'Time','id'=>'ID'],
        'lang_km' => ['title' => 'ប្រវត្តិចូលប្រព័ន្ធ','username'=>'អ្នកប្រើ','ip_address'=>'IP','success'=>'ជោគជ័យ','logged_in_at'=>'ពេលចូល','id'=>'លេខ'],
    ],
    [
        'slug' => 'audit-logs', 'resource' => 'AuditLog', 'model' => 'AuditLog',
        'columns' => [
            ['id','id','60px'],['user_id','user_id','110px'],['action','action','120px'],['model_type','model_type'],['model_id','model_id','110px'],['created_at','created_at','160px'],
        ],
        'lang' => ['title' => 'Audit Logs','user_id'=>'User','action'=>'Action','model_type'=>'Model','model_id'=>'Record ID','created_at'=>'When','id'=>'ID'],
        'lang_km' => ['title' => 'កំណត់ហេតុ','user_id'=>'អ្នកប្រើ','action'=>'សកម្មភាព','model_type'=>'ម៉ូដែល','model_id'=>'លេខកំណត់ត្រា','created_at'=>'ពេល','id'=>'លេខ'],
    ],
    [
        'slug' => 'notifications', 'resource' => 'Notification', 'model' => 'Notification',
        'columns' => [
            ['id','id','60px'],['title','title'],['type','type','120px'],['is_read','is_read','110px'],['created_at','created_at','160px'],
        ],
        'lang' => ['title' => 'Notifications','title_col'=>'Title','type'=>'Type','is_read'=>'Read','created_at'=>'When','id'=>'ID'],
        'lang_km' => ['title' => 'ការជូនដំណឹង','title_col'=>'ចំណងជើង','type'=>'ប្រភេទ','is_read'=>'បានអាន','created_at'=>'ពេល','id'=>'លេខ'],
    ],
    [
        'slug' => 'modifier-groups', 'resource' => 'ModifierGroup', 'model' => 'ModifierGroup',
        'columns' => [['id','id','60px'],['name','name'],['min_select','min_select','110px'],['max_select','max_select','110px'],['is_required','is_required','110px']],
        'lang' => ['title' => 'Modifier Groups','name'=>'Name','min_select'=>'Min','max_select'=>'Max','is_required'=>'Required','id'=>'ID'],
        'lang_km' => ['title' => 'ក្រុមជម្រើស','name'=>'ឈ្មោះ','min_select'=>'អប្បបរមា','max_select'=>'អតិបរមា','is_required'=>'ត្រូវការ','id'=>'លេខ'],
    ],
    [
        'slug' => 'modifiers', 'resource' => 'Modifier', 'model' => 'Modifier',
        'columns' => [['id','id','60px'],['name','name'],['price_delta','price_delta','110px'],['is_default','is_default','110px']],
        'lang' => ['title' => 'Modifiers','name'=>'Name','price_delta'=>'Price Δ','is_default'=>'Default','id'=>'ID'],
        'lang_km' => ['title' => 'ជម្រើស','name'=>'ឈ្មោះ','price_delta'=>'ភាពខុសប្លែក','is_default'=>'លំនាំដើម','id'=>'លេខ'],
    ],
    [
        'slug' => 'coupons', 'resource' => 'Coupon', 'model' => 'Coupon',
        'columns' => [['id','id','60px'],['code','code'],['promotion_id','promotion_id','110px'],['is_used','is_used','110px']],
        'lang' => ['title' => 'Coupons','code'=>'Code','promotion_id'=>'Promotion','is_used'=>'Used','id'=>'ID'],
        'lang_km' => ['title' => 'គូប៉ុង','code'=>'លេខកូដ','promotion_id'=>'ការផ្សព្វផ្សាយ','is_used'=>'បានប្រើ','id'=>'លេខ'],
    ],
    [
        'slug' => 'stock-movements', 'resource' => 'StockMovement', 'model' => 'StockMovement',
        'columns' => [['id','id','60px'],['stock_item_id','stock_item_id','110px'],['movement_type','movement_type','120px'],['quantity','quantity','110px'],['created_at','created_at','160px']],
        'lang' => ['title' => 'Stock Movements','stock_item_id'=>'Item','movement_type'=>'Type','quantity'=>'Qty','created_at'=>'When','id'=>'ID'],
        'lang_km' => ['title' => 'ចលនាស្តុក','stock_item_id'=>'ទំនិញ','movement_type'=>'ប្រភេទ','quantity'=>'បរិមាណ','created_at'=>'ពេល','id'=>'លេខ'],
    ],
    [
        'slug' => 'stock-adjustments', 'resource' => 'StockAdjustment', 'model' => 'StockAdjustment',
        'columns' => [['id','id','60px'],['adjustment_no','adjustment_no'],['reason','reason','120px'],['adjustment_date','adjustment_date','120px'],['status','status','110px']],
        'lang' => ['title' => 'Stock Adjustments','adjustment_no'=>'Adjustment #','reason'=>'Reason','adjustment_date'=>'Date','status'=>'Status','id'=>'ID'],
        'lang_km' => ['title' => 'ការកែប្រែស្តុក','adjustment_no'=>'លេខ','reason'=>'ហេតុផល','adjustment_date'=>'កាលបរិច្ឆេទ','status'=>'ស្ថានភាព','id'=>'លេខ'],
    ],
    [
        'slug' => 'purchase-orders', 'resource' => 'PurchaseOrder', 'model' => 'PurchaseOrder',
        'columns' => [['id','id','60px'],['po_no','po_no'],['supplier_id','supplier_id','110px'],['order_date','order_date','120px'],['status','status','110px'],['total_amount','total_amount','120px']],
        'lang' => ['title' => 'Purchase Orders','po_no'=>'PO #','supplier_id'=>'Supplier','order_date'=>'Date','status'=>'Status','total_amount'=>'Total','id'=>'ID'],
        'lang_km' => ['title' => 'ការបញ្ជាទិញ','po_no'=>'លេខ PO','supplier_id'=>'អ្នកផ្គត់ផ្គង់','order_date'=>'កាលបរិច្ឆេទ','status'=>'ស្ថានភាព','total_amount'=>'សរុប','id'=>'លេខ'],
    ],
    [
        'slug' => 'goods-receives', 'resource' => 'GoodsReceive', 'model' => 'GoodsReceive',
        'columns' => [['id','id','60px'],['gr_no','gr_no'],['supplier_id','supplier_id','110px'],['receive_date','receive_date','120px'],['status','status','110px']],
        'lang' => ['title' => 'Goods Receives','gr_no'=>'GR #','supplier_id'=>'Supplier','receive_date'=>'Date','status'=>'Status','id'=>'ID'],
        'lang_km' => ['title' => 'ការទទួលទំនិញ','gr_no'=>'លេខ GR','supplier_id'=>'អ្នកផ្គត់ផ្គង់','receive_date'=>'កាលបរិច្ឆេទ','status'=>'ស្ថានភាព','id'=>'លេខ'],
    ],
    [
        'slug' => 'journal-entries', 'resource' => 'JournalEntry', 'model' => 'JournalEntry',
        'columns' => [['id','id','60px'],['entry_no','entry_no'],['entry_date','entry_date','120px'],['description','description'],['total_debit','total_debit','120px']],
        'lang' => ['title' => 'Journal Entries','entry_no'=>'Entry #','entry_date'=>'Date','description'=>'Description','total_debit'=>'Total','id'=>'ID'],
        'lang_km' => ['title' => 'កំណត់ត្រាគណនី','entry_no'=>'លេខកំណត់ត្រា','entry_date'=>'កាលបរិច្ឆេទ','description'=>'ការពិពណ៌នា','total_debit'=>'សរុប','id'=>'លេខ'],
    ],
    [
        'slug' => 'code-sequences', 'resource' => 'CodeSequence', 'model' => 'CodeSequence',
        'columns' => [['id','id','60px'],['name','name'],['prefix','prefix','110px'],['next_number','next_number','120px'],['padding','padding','100px']],
        'lang' => ['title' => 'Code Sequences','name'=>'Name','prefix'=>'Prefix','next_number'=>'Next #','padding'=>'Padding','id'=>'ID'],
        'lang_km' => ['title' => 'លំដាប់លេខកូដ','name'=>'ឈ្មោះ','prefix'=>'បុព្វបទ','next_number'=>'លេខបន្ទាប់','padding'=>'បំពេញសូន្យ','id'=>'លេខ'],
    ],
];

$controllerTpl = <<<'PHP'
<?php

namespace App\Http\Controllers\Admin\__NS__;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\__MODEL__;

class __CLASS__Controller extends AbstractReadOnlyController
{
    protected function modelClass(): string { return __MODEL__::class; }
    protected function viewPath(): string { return 'admin.__SLUG_UNDER__'; }
    protected function routeName(): string { return 'admin.__SLUG__'; }
    protected function translationNamespace(): string { return '__SLUG_UNDER__'; }
}
PHP;

$indexTpl = <<<'BLADE'
@extends('admin._partials.crud_index')

@section('thead')
__THEAD__
@endsection

@section('columns_json')
[
__COLUMNS__
]
@endsection
BLADE;

$routeTpl = <<<'PHP'
<?php

use App\Http\Controllers\Admin\__NS__\__CLASS__Controller;
use Illuminate\Support\Facades\Route;

Route::get('__SLUG__', [__CLASS__Controller::class, 'index'])->name('__SLUG__.index');
PHP;

foreach ($modules as $m) {
    $slug = $m['slug'];
    $slugUnder = str_replace('-', '_', $slug);
    $resource = $m['resource'];
    $namespacePart = $resource . 's';

    $thead = '';
    $columns = '';
    foreach ($m['columns'] as $col) {
        $width = $col[2] ?? null;
        $widthAttr = $width ? "width: '$width', " : '';
        $thead .= "    <th>{{ __('{$slugUnder}.{$col[1]}') }}</th>\n";
        $columns .= "    { data: '{$col[0]}', name: '{$col[0]}', {$widthAttr}orderable: true },\n";
    }
    $columns = rtrim($columns, ",\n");

    $controller = strtr($controllerTpl, [
        '__NS__' => $namespacePart,
        '__CLASS__' => $resource,
        '__MODEL__' => $m['model'],
        '__SLUG__' => $slug,
        '__SLUG_UNDER__' => $slugUnder,
    ]);
    @mkdir("$base/app/Http/Controllers/Admin/$namespacePart", 0755, true);
    file_put_contents("$base/app/Http/Controllers/Admin/$namespacePart/{$resource}Controller.php", $controller);

    $indexBlade = strtr($indexTpl, ['__THEAD__' => rtrim($thead, "\n"), '__COLUMNS__' => $columns]);
    @mkdir("$base/resources/views/admin/$slugUnder", 0755, true);
    file_put_contents("$base/resources/views/admin/$slugUnder/index.blade.php", $indexBlade);

    $route = strtr($routeTpl, ['__NS__' => $namespacePart, '__CLASS__' => $resource, '__SLUG__' => $slug]);
    file_put_contents("$base/routes/admin/$slug.php", $route);

    foreach (['en' => $m['lang'], 'km' => $m['lang_km']] as $loc => $arr) {
        $php = "<?php\n\nreturn [\n";
        foreach ($arr as $k => $v) {
            $vEsc = str_replace("'", "\\'", $v);
            $php .= "    '$k' => '$vEsc',\n";
        }
        $php .= "];\n";
        file_put_contents("$base/lang/$loc/$slugUnder.php", $php);
    }
    echo "wrote $slug\n";
}
echo "done\n";
