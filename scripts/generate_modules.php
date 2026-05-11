<?php
/**
 * Generates per-module CRUD scaffolding (controller + route file +
 * index view + lang files) for all modules listed in $modules.
 *
 * Run: php scripts/generate_modules.php
 */

require __DIR__ . '/../vendor/autoload.php';

$base = dirname(__DIR__);

/**
 * Each module config:
 *  slug:             URL slug (kebab-case)
 *  resource:         singular Capitalized name used as Controller prefix
 *  model:            \App\Models\XYZ FQCN (relative to App\Models)
 *  fields:           name => ['type', 'label'?, 'required'?, 'col'?, 'maxlength'?, 'options'?, 'default'?]
 *  rules:            name => 'rules string' (rule for store/update; '__unique' triggers Rule::unique($table)->ignore())
 *  table:            DB table name (used for unique rules)
 *  unique:           [field => true] — fields that should have unique-ignore validation
 *  columns:          DataTable cells: [name, label_key (in module lang)] in order
 *  selectable:       'name' column — what to show in DataTable as the title
 *  enums:            optional override for enum select fields
 *  has_status_badge: column to render with active/inactive badge
 *  view_data:        php array literal for $this->viewData() — needs callable string with raw PHP
 *  parent_field:     not used yet
 */
$modules = [
    // ----- System
    [
        'slug' => 'tax-rates',
        'resource' => 'TaxRate',
        'model' => 'TaxRate',
        'fields' => [
            'name' => ['type' => 'text', 'required' => true, 'maxlength' => 255, 'col' => 'md-6'],
            'rate' => ['type' => 'number', 'step' => '0.0001', 'required' => true, 'col' => 'md-3'],
            'type' => ['type' => 'select', 'required' => true, 'col' => 'md-3', 'options' => [
                'tax' => 'Tax', 'service_charge' => 'Service Charge',
            ]],
            'is_inclusive' => ['type' => 'checkbox', 'col' => 'md-6', 'default' => false],
            'is_active'    => ['type' => 'checkbox', 'col' => 'md-6', 'default' => true],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'rate' => "['required','numeric']",
            'type' => "['required','in:tax,service_charge']",
            'is_inclusive' => "['nullable','boolean']",
            'is_active'    => "['nullable','boolean']",
        ],
        'columns' => [
            ['id', 'id', '60px'],
            ['name', 'name'],
            ['rate', 'rate'],
            ['type', 'type'],
            ['is_active', 'is_active', '110px', true /*badge*/],
        ],
        'lang' => [
            'title' => 'Tax Rates', 'create_title' => 'New Tax Rate', 'edit_title' => 'Edit Tax Rate',
            'name' => 'Name', 'rate' => 'Rate', 'type' => 'Type',
            'is_inclusive' => 'Inclusive', 'is_active' => 'Active',
        ],
        'lang_km' => [
            'title' => 'អត្រាពន្ធ', 'create_title' => 'បន្ថែមអត្រាពន្ធ', 'edit_title' => 'កែប្រែអត្រាពន្ធ',
            'name' => 'ឈ្មោះ', 'rate' => 'អត្រា', 'type' => 'ប្រភេទ',
            'is_inclusive' => 'រាប់បញ្ចូល', 'is_active' => 'សកម្ម',
        ],
    ],
    [
        'slug' => 'payment-methods',
        'resource' => 'PaymentMethod',
        'model' => 'PaymentMethod',
        'fields' => [
            'code' => ['type' => 'text', 'required' => true, 'maxlength' => 50, 'col' => 'md-3'],
            'name' => ['type' => 'text', 'required' => true, 'maxlength' => 255, 'col' => 'md-6'],
            'type' => ['type' => 'select', 'required' => true, 'col' => 'md-3', 'options' => [
                'cash' => 'Cash', 'aba' => 'ABA', 'khqr' => 'KHQR', 'wing' => 'Wing',
                'bank_transfer' => 'Bank Transfer', 'card' => 'Card', 'other' => 'Other',
            ]],
            'requires_reference' => ['type' => 'checkbox', 'col' => 'md-4'],
            'is_online' => ['type' => 'checkbox', 'col' => 'md-4'],
            'is_active' => ['type' => 'checkbox', 'col' => 'md-4', 'default' => true],
        ],
        'rules' => [
            'code' => "['required','string','max:50']",
            'name' => "['required','string','max:255']",
            'type' => "['required','in:cash,aba,khqr,wing,bank_transfer,card,other']",
            'requires_reference' => "['nullable','boolean']",
            'is_online' => "['nullable','boolean']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [
            ['id','id','60px'],['code','code'],['name','name'],['type','type'],
            ['is_active','is_active','110px',true],
        ],
        'lang' => ['title' => 'Payment Methods','create_title'=>'New Payment Method','edit_title'=>'Edit Payment Method',
            'code'=>'Code','name'=>'Name','type'=>'Type','requires_reference'=>'Requires Reference','is_online'=>'Online','is_active'=>'Active'],
        'lang_km' => ['title' => 'វិធីបង់ប្រាក់','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'code'=>'លេខកូដ','name'=>'ឈ្មោះ','type'=>'ប្រភេទ','requires_reference'=>'ត្រូវការឯកសារយោង','is_online'=>'អនឡាញ','is_active'=>'សកម្ម'],
    ],
    [
        'slug' => 'printers',
        'resource' => 'Printer',
        'model' => 'Printer',
        'fields' => [
            'name' => ['type' => 'text', 'required' => true, 'maxlength' => 255, 'col' => 'md-6'],
            'printer_type' => ['type' => 'select', 'required' => true, 'col' => 'md-3', 'options' => [
                'network' => 'Network', 'usb' => 'USB', 'bluetooth' => 'Bluetooth',
            ]],
            'paper_size' => ['type' => 'select', 'col' => 'md-3', 'options' => [
                '80mm' => '80mm', '58mm' => '58mm', 'A4' => 'A4',
            ]],
            'ip_address' => ['type' => 'text', 'maxlength' => 100, 'col' => 'md-6'],
            'port' => ['type' => 'number', 'col' => 'md-6'],
            'is_default' => ['type' => 'checkbox', 'col' => 'md-6'],
            'is_active' => ['type' => 'checkbox', 'col' => 'md-6', 'default' => true],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'printer_type' => "['required','in:network,usb,bluetooth']",
            'paper_size' => "['nullable','string','max:50']",
            'ip_address' => "['nullable','string','max:100']",
            'port' => "['nullable','integer']",
            'is_default' => "['nullable','boolean']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['name','name'],['printer_type','printer_type'],['ip_address','ip_address'],['is_active','is_active','110px',true]],
        'lang' => ['title'=>'Printers','create_title'=>'New Printer','edit_title'=>'Edit Printer',
            'name'=>'Name','printer_type'=>'Type','paper_size'=>'Paper Size','ip_address'=>'IP Address','port'=>'Port','is_default'=>'Default','is_active'=>'Active'],
        'lang_km' => ['title'=>'ម៉ាស៊ីនបោះពុម្ព','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'name'=>'ឈ្មោះ','printer_type'=>'ប្រភេទ','paper_size'=>'ទំហំក្រដាស','ip_address'=>'អាសយដ្ឋាន IP','port'=>'ច្រក','is_default'=>'លំនាំដើម','is_active'=>'សកម្ម'],
    ],
    // ----- RBAC
    [
        'slug' => 'roles',
        'resource' => 'Role',
        'model' => 'Role',
        'fields' => [
            'name' => ['type'=>'text','required'=>true,'maxlength'=>255,'col'=>'md-6'],
            'slug' => ['type'=>'text','required'=>true,'maxlength'=>255,'col'=>'md-6'],
            'description' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'is_system' => ['type'=>'checkbox','col'=>'md-6'],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'slug' => "['required','string','max:255']",
            'description' => "['nullable','string']",
            'is_system' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['name','name'],['slug','slug'],['is_system','is_system','110px',true]],
        'lang' => ['title'=>'Roles','create_title'=>'New Role','edit_title'=>'Edit Role',
            'name'=>'Name','slug'=>'Slug','description'=>'Description','is_system'=>'System'],
        'lang_km' => ['title'=>'តួនាទី','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'name'=>'ឈ្មោះ','slug'=>'លេខកូដ','description'=>'ការពិពណ៌នា','is_system'=>'ប្រព័ន្ធ'],
    ],
    [
        'slug' => 'permissions',
        'resource' => 'Permission',
        'model' => 'Permission',
        'fields' => [
            'module' => ['type'=>'text','required'=>true,'maxlength'=>80,'col'=>'md-3'],
            'name' => ['type'=>'text','required'=>true,'maxlength'=>255,'col'=>'md-5'],
            'slug' => ['type'=>'text','required'=>true,'maxlength'=>255,'col'=>'md-4'],
            'description' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
        ],
        'rules' => [
            'module' => "['required','string','max:80']",
            'name' => "['required','string','max:255']",
            'slug' => "['required','string','max:255']",
            'description' => "['nullable','string']",
        ],
        'columns' => [['id','id','60px'],['module','module'],['name','name'],['slug','slug']],
        'lang' => ['title'=>'Permissions','create_title'=>'New Permission','edit_title'=>'Edit Permission',
            'module'=>'Module','name'=>'Name','slug'=>'Slug','description'=>'Description'],
        'lang_km' => ['title'=>'សិទ្ធិ','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'module'=>'ម៉ូឌុល','name'=>'ឈ្មោះ','slug'=>'លេខកូដ','description'=>'ការពិពណ៌នា'],
    ],
    [
        'slug' => 'users',
        'resource' => 'User',
        'model' => 'User',
        'fields' => [
            'name' => ['type'=>'text','required'=>true,'col'=>'md-6'],
            'username' => ['type'=>'text','required'=>true,'col'=>'md-6'],
            'email' => ['type'=>'email','col'=>'md-6'],
            'phone' => ['type'=>'text','col'=>'md-6'],
            'password' => ['type'=>'password','col'=>'md-6'],
            'status' => ['type'=>'select','col'=>'md-6','options'=>[
                'active'=>'Active','inactive'=>'Inactive','locked'=>'Locked',
            ],'required'=>true,'default'=>'active'],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'username' => "['required','string','max:100']",
            'email' => "['nullable','email','max:255']",
            'phone' => "['nullable','string','max:50']",
            'password' => "[isCreateMode ? 'required' : 'nullable','string','min:6','max:255']",
            'status' => "['required','in:active,inactive,locked']",
        ],
        'rules_dynamic' => true, // password requirement depends on create vs update
        'columns' => [['id','id','60px'],['name','name'],['username','username'],['email','email'],['status','status','110px']],
        'lang' => ['title'=>'Users','create_title'=>'New User','edit_title'=>'Edit User',
            'name'=>'Name','username'=>'Username','email'=>'Email','phone'=>'Phone','password'=>'Password','status'=>'Status'],
        'lang_km' => ['title'=>'អ្នកប្រើប្រាស់','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'name'=>'ឈ្មោះ','username'=>'ឈ្មោះគណនី','email'=>'អ៊ីមែល','phone'=>'ទូរស័ព្ទ','password'=>'ពាក្យសម្ងាត់','status'=>'ស្ថានភាព'],
        'hash_password' => true,
    ],
    [
        'slug' => 'staff',
        'resource' => 'Staff',
        'model' => 'Staff',
        'fields' => [
            'staff_code' => ['type'=>'text','required'=>true,'maxlength'=>50,'col'=>'md-4'],
            'name' => ['type'=>'text','required'=>true,'col'=>'md-8'],
            'phone' => ['type'=>'text','col'=>'md-4'],
            'email' => ['type'=>'email','col'=>'md-4'],
            'position' => ['type'=>'text','col'=>'md-4'],
            'address' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'hire_date' => ['type'=>'date','col'=>'md-4'],
            'status' => ['type'=>'select','col'=>'md-4','options'=>['active'=>'Active','inactive'=>'Inactive','terminated'=>'Terminated'],'required'=>true,'default'=>'active'],
        ],
        'rules' => [
            'staff_code' => "['required','string','max:50']",
            'name' => "['required','string','max:255']",
            'phone' => "['nullable','string','max:50']",
            'email' => "['nullable','email','max:255']",
            'position' => "['nullable','string','max:255']",
            'address' => "['nullable','string']",
            'hire_date' => "['nullable','date']",
            'status' => "['required','in:active,inactive,terminated']",
        ],
        'columns' => [['id','id','60px'],['staff_code','staff_code'],['name','name'],['phone','phone'],['position','position'],['status','status']],
        'lang' => ['title'=>'Staff','create_title'=>'New Staff','edit_title'=>'Edit Staff',
            'staff_code'=>'Staff Code','name'=>'Name','phone'=>'Phone','email'=>'Email','position'=>'Position','address'=>'Address','hire_date'=>'Hire Date','status'=>'Status'],
        'lang_km' => ['title'=>'បុគ្គលិក','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'staff_code'=>'លេខកូដ','name'=>'ឈ្មោះ','phone'=>'ទូរស័ព្ទ','email'=>'អ៊ីមែល','position'=>'តួនាទី','address'=>'អាសយដ្ឋាន','hire_date'=>'កាលបរិច្ឆេទចូលធ្វើ','status'=>'ស្ថានភាព'],
    ],
    // ----- Menu
    [
        'slug' => 'menu-categories',
        'resource' => 'MenuCategory',
        'model' => 'MenuCategory',
        'fields' => [
            'code' => ['type'=>'text','col'=>'md-3','maxlength'=>50],
            'name' => ['type'=>'text','required'=>true,'col'=>'md-9'],
            'description' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'sort_order' => ['type'=>'number','col'=>'md-6','default'=>0],
            'is_active' => ['type'=>'checkbox','col'=>'md-6','default'=>true],
        ],
        'rules' => [
            'code' => "['nullable','string','max:50']",
            'name' => "['required','string','max:255']",
            'description' => "['nullable','string']",
            'sort_order' => "['nullable','integer']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['code','code'],['name','name'],['sort_order','sort_order','110px'],['is_active','is_active','110px',true]],
        'lang' => ['title'=>'Menu Categories','create_title'=>'New Category','edit_title'=>'Edit Category',
            'code'=>'Code','name'=>'Name','description'=>'Description','sort_order'=>'Sort Order','is_active'=>'Active'],
        'lang_km' => ['title'=>'ប្រភេទម្ហូប','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'code'=>'លេខកូដ','name'=>'ឈ្មោះ','description'=>'ការពិពណ៌នា','sort_order'=>'លំដាប់','is_active'=>'សកម្ម'],
    ],
    [
        'slug' => 'menu-items',
        'resource' => 'MenuItem',
        'model' => 'MenuItem',
        'fields' => [
            'item_code' => ['type'=>'text','required'=>true,'maxlength'=>50,'col'=>'md-3'],
            'name' => ['type'=>'text','required'=>true,'col'=>'md-9'],
            'description' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'sale_price' => ['type'=>'number','step'=>'0.01','required'=>true,'col'=>'md-3','default'=>0],
            'cost_price' => ['type'=>'number','step'=>'0.01','col'=>'md-3','default'=>0],
            'status' => ['type'=>'select','col'=>'md-3','options'=>['active'=>'Active','sold_out'=>'Sold Out','inactive'=>'Inactive'],'default'=>'active','required'=>true],
            'sort_order' => ['type'=>'number','col'=>'md-3','default'=>0],
            'is_combo' => ['type'=>'checkbox','col'=>'md-3'],
            'track_inventory' => ['type'=>'checkbox','col'=>'md-3'],
            'is_available' => ['type'=>'checkbox','col'=>'md-3','default'=>true],
        ],
        'rules' => [
            'item_code' => "['required','string','max:50']",
            'name' => "['required','string','max:255']",
            'description' => "['nullable','string']",
            'sale_price' => "['required','numeric','min:0']",
            'cost_price' => "['nullable','numeric','min:0']",
            'status' => "['required','in:active,sold_out,inactive']",
            'sort_order' => "['nullable','integer']",
            'is_combo' => "['nullable','boolean']",
            'track_inventory' => "['nullable','boolean']",
            'is_available' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['item_code','item_code'],['name','name'],['sale_price','sale_price','110px'],['status','status','110px']],
        'lang' => ['title'=>'Menu Items','create_title'=>'New Item','edit_title'=>'Edit Item',
            'item_code'=>'Item Code','name'=>'Name','description'=>'Description','sale_price'=>'Sale Price','cost_price'=>'Cost Price','status'=>'Status','sort_order'=>'Sort Order','is_combo'=>'Combo','track_inventory'=>'Track Inventory','is_available'=>'Available'],
        'lang_km' => ['title'=>'ម្ហូប','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'item_code'=>'លេខកូដ','name'=>'ឈ្មោះ','description'=>'ការពិពណ៌នា','sale_price'=>'តម្លៃលក់','cost_price'=>'តម្លៃដើម','status'=>'ស្ថានភាព','sort_order'=>'លំដាប់','is_combo'=>'កំបាំង','track_inventory'=>'តាមដានស្តុក','is_available'=>'អាចបាន'],
    ],
    // ----- Tables/Customers
    [
        'slug' => 'zones',
        'resource' => 'Zone',
        'model' => 'Zone',
        'fields' => [
            'name' => ['type'=>'text','required'=>true,'col'=>'md-6'],
            'description' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'sort_order' => ['type'=>'number','col'=>'md-6','default'=>0],
            'is_active' => ['type'=>'checkbox','col'=>'md-6','default'=>true],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'description' => "['nullable','string']",
            'sort_order' => "['nullable','integer']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['name','name'],['sort_order','sort_order','110px'],['is_active','is_active','110px',true]],
        'lang' => ['title'=>'Zones','create_title'=>'New Zone','edit_title'=>'Edit Zone',
            'name'=>'Name','description'=>'Description','sort_order'=>'Sort Order','is_active'=>'Active'],
        'lang_km' => ['title'=>'តំបន់','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'name'=>'ឈ្មោះ','description'=>'ការពិពណ៌នា','sort_order'=>'លំដាប់','is_active'=>'សកម្ម'],
    ],
    [
        'slug' => 'dining-tables',
        'resource' => 'DiningTable',
        'model' => 'DiningTable',
        'fields' => [
            'table_code' => ['type'=>'text','required'=>true,'maxlength'=>50,'col'=>'md-3'],
            'table_no' => ['type'=>'text','required'=>true,'col'=>'md-3'],
            'capacity' => ['type'=>'number','col'=>'md-3','default'=>1,'required'=>true],
            'status' => ['type'=>'select','col'=>'md-3','options'=>[
                'available'=>'Available','occupied'=>'Occupied','reserved'=>'Reserved','inactive'=>'Inactive',
            ],'default'=>'available','required'=>true],
            'is_active' => ['type'=>'checkbox','col'=>'md-6','default'=>true],
        ],
        'rules' => [
            'table_code' => "['required','string','max:50']",
            'table_no' => "['required','string','max:50']",
            'capacity' => "['required','integer','min:1']",
            'status' => "['required','in:available,occupied,reserved,inactive']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['table_code','table_code'],['table_no','table_no'],['capacity','capacity','110px'],['status','status','110px']],
        'lang' => ['title'=>'Dining Tables','create_title'=>'New Table','edit_title'=>'Edit Table',
            'table_code'=>'Code','table_no'=>'Table No','capacity'=>'Capacity','status'=>'Status','is_active'=>'Active'],
        'lang_km' => ['title'=>'តុ','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'table_code'=>'លេខកូដ','table_no'=>'លេខតុ','capacity'=>'ចំនួនកៅអី','status'=>'ស្ថានភាព','is_active'=>'សកម្ម'],
    ],
    [
        'slug' => 'customers',
        'resource' => 'Customer',
        'model' => 'Customer',
        'fields' => [
            'customer_code' => ['type'=>'text','required'=>true,'maxlength'=>50,'col'=>'md-3'],
            'name' => ['type'=>'text','required'=>true,'col'=>'md-9'],
            'phone' => ['type'=>'text','col'=>'md-4'],
            'email' => ['type'=>'email','col'=>'md-4'],
            'membership_no' => ['type'=>'text','col'=>'md-4'],
            'address' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'points' => ['type'=>'number','col'=>'md-3','default'=>0],
            'credit_limit' => ['type'=>'number','step'=>'0.01','col'=>'md-3','default'=>0],
            'status' => ['type'=>'select','col'=>'md-3','options'=>['active'=>'Active','inactive'=>'Inactive','blocked'=>'Blocked'],'default'=>'active','required'=>true],
            'is_blacklisted' => ['type'=>'checkbox','col'=>'md-3'],
            'notes' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
        ],
        'rules' => [
            'customer_code' => "['required','string','max:50']",
            'name' => "['required','string','max:255']",
            'phone' => "['nullable','string','max:50']",
            'email' => "['nullable','email','max:255']",
            'membership_no' => "['nullable','string','max:80']",
            'address' => "['nullable','string']",
            'points' => "['nullable','integer','min:0']",
            'credit_limit' => "['nullable','numeric','min:0']",
            'status' => "['required','in:active,inactive,blocked']",
            'is_blacklisted' => "['nullable','boolean']",
            'notes' => "['nullable','string']",
        ],
        'columns' => [['id','id','60px'],['customer_code','customer_code'],['name','name'],['phone','phone'],['email','email'],['status','status','110px']],
        'lang' => ['title'=>'Customers','create_title'=>'New Customer','edit_title'=>'Edit Customer',
            'customer_code'=>'Code','name'=>'Name','phone'=>'Phone','email'=>'Email','membership_no'=>'Membership No','address'=>'Address','points'=>'Points','credit_limit'=>'Credit Limit','status'=>'Status','is_blacklisted'=>'Blacklisted','notes'=>'Notes'],
        'lang_km' => ['title'=>'អតិថិជន','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'customer_code'=>'លេខកូដ','name'=>'ឈ្មោះ','phone'=>'ទូរស័ព្ទ','email'=>'អ៊ីមែល','membership_no'=>'លេខសមាជិក','address'=>'អាសយដ្ឋាន','points'=>'ពិន្ទុ','credit_limit'=>'កម្រិតឥណទាន','status'=>'ស្ថានភាព','is_blacklisted'=>'បញ្ជីខ្មៅ','notes'=>'កំណត់ចំណាំ'],
    ],
    // ----- Inventory
    [
        'slug' => 'inventory-categories',
        'resource' => 'InventoryCategory',
        'model' => 'InventoryCategory',
        'fields' => [
            'name' => ['type'=>'text','required'=>true,'col'=>'md-6'],
            'description' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'is_active' => ['type'=>'checkbox','col'=>'md-6','default'=>true],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'description' => "['nullable','string']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['name','name'],['is_active','is_active','110px',true]],
        'lang' => ['title'=>'Inventory Categories','create_title'=>'New Category','edit_title'=>'Edit Category',
            'name'=>'Name','description'=>'Description','is_active'=>'Active'],
        'lang_km' => ['title'=>'ប្រភេទស្តុក','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'name'=>'ឈ្មោះ','description'=>'ការពិពណ៌នា','is_active'=>'សកម្ម'],
    ],
    [
        'slug' => 'units',
        'resource' => 'Unit',
        'model' => 'Unit',
        'fields' => [
            'name' => ['type'=>'text','required'=>true,'col'=>'md-6'],
            'symbol' => ['type'=>'text','required'=>true,'maxlength'=>20,'col'=>'md-3'],
            'unit_type' => ['type'=>'select','col'=>'md-3','options'=>['weight'=>'Weight','volume'=>'Volume','piece'=>'Piece']],
            'is_base' => ['type'=>'checkbox','col'=>'md-6'],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'symbol' => "['required','string','max:20']",
            'unit_type' => "['nullable','in:weight,volume,piece']",
            'is_base' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['name','name'],['symbol','symbol'],['unit_type','unit_type'],['is_base','is_base','110px',true]],
        'lang' => ['title'=>'Units','create_title'=>'New Unit','edit_title'=>'Edit Unit',
            'name'=>'Name','symbol'=>'Symbol','unit_type'=>'Type','is_base'=>'Base Unit'],
        'lang_km' => ['title'=>'ឯកតា','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'name'=>'ឈ្មោះ','symbol'=>'និមិត្តសញ្ញា','unit_type'=>'ប្រភេទ','is_base'=>'ឯកតាមូលដ្ឋាន'],
    ],
    [
        'slug' => 'stock-items',
        'resource' => 'StockItem',
        'model' => 'StockItem',
        'fields' => [
            'item_code' => ['type'=>'text','required'=>true,'maxlength'=>80,'col'=>'md-3'],
            'name' => ['type'=>'text','required'=>true,'col'=>'md-9'],
            'purchase_price' => ['type'=>'number','step'=>'0.01','col'=>'md-3','default'=>0],
            'quantity_on_hand' => ['type'=>'number','step'=>'0.0001','col'=>'md-3','default'=>0],
            'reorder_level' => ['type'=>'number','step'=>'0.0001','col'=>'md-3','default'=>0],
            'reorder_quantity' => ['type'=>'number','step'=>'0.0001','col'=>'md-3','default'=>0],
            'expiry_date' => ['type'=>'date','col'=>'md-6'],
            'is_ingredient' => ['type'=>'checkbox','col'=>'md-3','default'=>true],
            'is_active' => ['type'=>'checkbox','col'=>'md-3','default'=>true],
        ],
        'rules' => [
            'item_code' => "['required','string','max:80']",
            'name' => "['required','string','max:255']",
            'purchase_price' => "['nullable','numeric','min:0']",
            'quantity_on_hand' => "['nullable','numeric']",
            'reorder_level' => "['nullable','numeric','min:0']",
            'reorder_quantity' => "['nullable','numeric','min:0']",
            'expiry_date' => "['nullable','date']",
            'is_ingredient' => "['nullable','boolean']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['item_code','item_code'],['name','name'],['quantity_on_hand','quantity_on_hand','110px'],['is_active','is_active','110px',true]],
        'lang' => ['title'=>'Stock Items','create_title'=>'New Stock Item','edit_title'=>'Edit Stock Item',
            'item_code'=>'Item Code','name'=>'Name','purchase_price'=>'Purchase Price','quantity_on_hand'=>'On Hand','reorder_level'=>'Reorder Level','reorder_quantity'=>'Reorder Qty','expiry_date'=>'Expiry Date','is_ingredient'=>'Ingredient','is_active'=>'Active'],
        'lang_km' => ['title'=>'ស្តុក','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'item_code'=>'លេខកូដ','name'=>'ឈ្មោះ','purchase_price'=>'តម្លៃទិញ','quantity_on_hand'=>'បរិមាណ','reorder_level'=>'កម្រិតបញ្ជាទិញឡើងវិញ','reorder_quantity'=>'បរិមាណបញ្ជាទិញ','expiry_date'=>'កាលបរិច្ឆេទផុតកំណត់','is_ingredient'=>'គ្រឿងផ្សំ','is_active'=>'សកម្ម'],
    ],
    [
        'slug' => 'suppliers',
        'resource' => 'Supplier',
        'model' => 'Supplier',
        'fields' => [
            'supplier_code' => ['type'=>'text','required'=>true,'maxlength'=>80,'col'=>'md-3'],
            'name' => ['type'=>'text','required'=>true,'col'=>'md-9'],
            'contact_person' => ['type'=>'text','col'=>'md-4'],
            'phone' => ['type'=>'text','col'=>'md-4'],
            'email' => ['type'=>'email','col'=>'md-4'],
            'address' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
            'status' => ['type'=>'select','col'=>'md-6','options'=>['active'=>'Active','inactive'=>'Inactive','blocked'=>'Blocked'],'default'=>'active','required'=>true],
        ],
        'rules' => [
            'supplier_code' => "['required','string','max:80']",
            'name' => "['required','string','max:255']",
            'contact_person' => "['nullable','string','max:255']",
            'phone' => "['nullable','string','max:50']",
            'email' => "['nullable','email','max:255']",
            'address' => "['nullable','string']",
            'status' => "['required','in:active,inactive,blocked']",
        ],
        'columns' => [['id','id','60px'],['supplier_code','supplier_code'],['name','name'],['phone','phone'],['email','email'],['status','status','110px']],
        'lang' => ['title'=>'Suppliers','create_title'=>'New Supplier','edit_title'=>'Edit Supplier',
            'supplier_code'=>'Code','name'=>'Name','contact_person'=>'Contact','phone'=>'Phone','email'=>'Email','address'=>'Address','status'=>'Status'],
        'lang_km' => ['title'=>'អ្នកផ្គត់ផ្គង់','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'supplier_code'=>'លេខកូដ','name'=>'ឈ្មោះ','contact_person'=>'អ្នកទំនាក់ទំនង','phone'=>'ទូរស័ព្ទ','email'=>'អ៊ីមែល','address'=>'អាសយដ្ឋាន','status'=>'ស្ថានភាព'],
    ],
    [
        'slug' => 'accounts',
        'resource' => 'Account',
        'model' => 'Account',
        'fields' => [
            'account_code' => ['type'=>'text','required'=>true,'maxlength'=>50,'col'=>'md-4'],
            'name' => ['type'=>'text','required'=>true,'col'=>'md-8'],
            'account_type' => ['type'=>'select','col'=>'md-6','required'=>true,'options'=>[
                'asset'=>'Asset','liability'=>'Liability','equity'=>'Equity','revenue'=>'Revenue','expense'=>'Expense','cost_of_goods_sold'=>'COGS',
            ]],
            'is_active' => ['type'=>'checkbox','col'=>'md-6','default'=>true],
        ],
        'rules' => [
            'account_code' => "['required','string','max:50']",
            'name' => "['required','string','max:255']",
            'account_type' => "['required','in:asset,liability,equity,revenue,expense,cost_of_goods_sold']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['account_code','account_code'],['name','name'],['account_type','account_type'],['is_active','is_active','110px',true]],
        'lang' => ['title'=>'Accounts','create_title'=>'New Account','edit_title'=>'Edit Account',
            'account_code'=>'Code','name'=>'Name','account_type'=>'Type','is_active'=>'Active'],
        'lang_km' => ['title'=>'គណនី','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'account_code'=>'លេខកូដ','name'=>'ឈ្មោះ','account_type'=>'ប្រភេទ','is_active'=>'សកម្ម'],
    ],
    [
        'slug' => 'expenses',
        'resource' => 'Expense',
        'model' => 'Expense',
        'fields' => [
            'expense_no' => ['type'=>'text','required'=>true,'maxlength'=>80,'col'=>'md-4'],
            'title' => ['type'=>'text','required'=>true,'col'=>'md-8'],
            'amount' => ['type'=>'number','step'=>'0.01','required'=>true,'col'=>'md-3'],
            'expense_date' => ['type'=>'date','col'=>'md-3'],
            'reference_no' => ['type'=>'text','col'=>'md-3'],
            'description' => ['type'=>'textarea','col'=>'md-12','rows'=>2],
        ],
        'rules' => [
            'expense_no' => "['required','string','max:80']",
            'title' => "['required','string','max:255']",
            'amount' => "['required','numeric','min:0']",
            'expense_date' => "['nullable','date']",
            'reference_no' => "['nullable','string','max:255']",
            'description' => "['nullable','string']",
        ],
        'columns' => [['id','id','60px'],['expense_no','expense_no'],['title','title'],['amount','amount','120px'],['expense_date','expense_date','120px']],
        'lang' => ['title'=>'Expenses','create_title'=>'New Expense','edit_title'=>'Edit Expense',
            'expense_no'=>'Expense No','title'=>'Title','amount'=>'Amount','expense_date'=>'Date','reference_no'=>'Ref. No','description'=>'Description'],
        'lang_km' => ['title'=>'ការចំណាយ','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'expense_no'=>'លេខចំណាយ','title'=>'ចំណងជើង','amount'=>'ចំនួនទឹកប្រាក់','expense_date'=>'កាលបរិច្ឆេទ','reference_no'=>'លេខយោង','description'=>'ការពិពណ៌នា'],
    ],
    [
        'slug' => 'promotions',
        'resource' => 'Promotion',
        'model' => 'Promotion',
        'fields' => [
            'name' => ['type'=>'text','required'=>true,'col'=>'md-6'],
            'promotion_type' => ['type'=>'select','required'=>true,'col'=>'md-3','options'=>[
                'bill_discount'=>'Bill Discount','item_discount'=>'Item Discount','coupon'=>'Coupon','buy_x_get_y'=>'Buy X Get Y','happy_hour'=>'Happy Hour',
            ]],
            'discount_type' => ['type'=>'select','required'=>true,'col'=>'md-3','options'=>[
                'percent'=>'Percent','fixed'=>'Fixed','free_item'=>'Free Item',
            ]],
            'discount_value' => ['type'=>'number','step'=>'0.01','col'=>'md-3','default'=>0,'required'=>true],
            'min_spend' => ['type'=>'number','step'=>'0.01','col'=>'md-3','default'=>0],
            'start_date' => ['type'=>'date','col'=>'md-3'],
            'end_date' => ['type'=>'date','col'=>'md-3'],
            'is_active' => ['type'=>'checkbox','col'=>'md-6','default'=>true],
        ],
        'rules' => [
            'name' => "['required','string','max:255']",
            'promotion_type' => "['required','in:bill_discount,item_discount,coupon,buy_x_get_y,happy_hour']",
            'discount_type' => "['required','in:percent,fixed,free_item']",
            'discount_value' => "['required','numeric','min:0']",
            'min_spend' => "['nullable','numeric','min:0']",
            'start_date' => "['nullable','date']",
            'end_date' => "['nullable','date','after_or_equal:start_date']",
            'is_active' => "['nullable','boolean']",
        ],
        'columns' => [['id','id','60px'],['name','name'],['promotion_type','promotion_type'],['discount_type','discount_type'],['is_active','is_active','110px',true]],
        'lang' => ['title'=>'Promotions','create_title'=>'New Promotion','edit_title'=>'Edit Promotion',
            'name'=>'Name','promotion_type'=>'Promotion Type','discount_type'=>'Discount Type','discount_value'=>'Discount Value','min_spend'=>'Min Spend','start_date'=>'Start Date','end_date'=>'End Date','is_active'=>'Active'],
        'lang_km' => ['title'=>'ការផ្សព្វផ្សាយ','create_title'=>'បន្ថែម','edit_title'=>'កែប្រែ',
            'name'=>'ឈ្មោះ','promotion_type'=>'ប្រភេទ','discount_type'=>'ប្រភេទបញ្ចុះតម្លៃ','discount_value'=>'តម្លៃបញ្ចុះ','min_spend'=>'ការចំណាយអប្បបរមា','start_date'=>'ចាប់ផ្តើម','end_date'=>'បញ្ចប់','is_active'=>'សកម្ម'],
    ],
];

// Common include for all the templates so we can read them.
$controllerTpl = <<<'PHP'
<?php

namespace App\Http\Controllers\Admin\__NS__;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\__MODEL__;
use Illuminate\Http\Request;

class __CLASS__Controller extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return __MODEL__::class;
    }

    protected function viewPath(): string
    {
        return 'admin.__SLUG_DOT__';
    }

    protected function routeName(): string
    {
        return 'admin.__SLUG__';
    }

    protected function translationNamespace(): string
    {
        return '__SLUG_UNDER__';
    }

    protected function rules(?int $id = null): array
    {
__RULES__
    }

    protected function fields(): array
    {
        return __FIELDS_PHP__;
    }

    protected function configureDataTable($dt)
    {
        return $dt
            __DT_EDITS__
            ->addColumn('actions', fn ($row) => view(
                'admin._partials.datatable_actions',
                ['row' => $row, 'routeName' => $this->routeName()]
            )->render())
            ->rawColumns([__RAW_COLS__]);
    }
__EXTRA_METHODS__
}
PHP;

$indexTpl = <<<'BLADE'
@extends('admin._partials.crud_index')

@section('thead')
__THEAD__
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
__COLUMNS__
    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection
BLADE;

$routeTpl = <<<'PHP'
<?php

use App\Http\Controllers\Admin\__NS__\__CLASS__Controller;
use Illuminate\Support\Facades\Route;

Route::resource('__SLUG__', __CLASS__Controller::class)->except(['show']);
PHP;

function exportPhpArray(array $a, int $indent = 8): string
{
    $pad = str_repeat(' ', $indent);
    $lines = [];
    foreach ($a as $k => $v) {
        $key = is_int($k) ? '' : "'$k' => ";
        if (is_array($v)) {
            $lines[] = $pad . $key . exportPhpArray($v, $indent + 4);
        } elseif (is_bool($v)) {
            $lines[] = $pad . $key . ($v ? 'true' : 'false') . ',';
        } elseif (is_int($v) || is_float($v)) {
            $lines[] = $pad . $key . $v . ',';
        } elseif (is_null($v)) {
            $lines[] = $pad . $key . 'null,';
        } else {
            $lines[] = $pad . $key . "'" . str_replace("'", "\\'", (string) $v) . "',";
        }
    }
    if (empty($lines)) {
        return '[]' . ',';
    }
    return "[\n" . implode("\n", $lines) . "\n" . str_repeat(' ', $indent - 4) . '],';
}

foreach ($modules as $m) {
    $slug = $m['slug'];                                  // e.g. tax-rates
    $slugDot = str_replace('-', '-', $slug);             // for blade folder
    $slugUnder = str_replace('-', '_', $slug);
    $resource = $m['resource'];
    $namespacePart = $resource . 's';                    // e.g. TaxRates
    $modelClass = $m['model'];

    // RULES
    $rulesLines = [];
    if (!empty($m['rules_dynamic'])) {
        $rulesLines[] = '        $isCreate = $id === null;';
        $rulesLines[] = '        $isCreateMode = $isCreate;';
        $rulesLines[] = '        return [';
        foreach ($m['rules'] as $f => $r) {
            $r2 = str_replace('isCreateMode', '$isCreateMode', $r);
            $rulesLines[] = "            '$f' => $r2,";
        }
        $rulesLines[] = '        ];';
    } else {
        $rulesLines[] = '        return [';
        foreach ($m['rules'] as $f => $r) {
            $rulesLines[] = "            '$f' => $r,";
        }
        $rulesLines[] = '        ];';
    }
    $rulesPhp = implode("\n", $rulesLines);

    // FIELDS
    $fieldsPhp = exportPhpArray($m['fields'], 12);
    $fieldsPhp = "[\n" . substr($fieldsPhp, 2);            // remove the leading "[,\n"
    // exportPhpArray adds trailing "],". Strip it and re-close.
    $fieldsPhp = rtrim($fieldsPhp, ', ');
    if (!str_ends_with($fieldsPhp, ']')) $fieldsPhp .= ']';

    // Better: build manually for clarity
    $fieldsPhp = "[\n";
    foreach ($m['fields'] as $f => $cfg) {
        $fieldsPhp .= "            '$f' => [\n";
        foreach ($cfg as $k => $v) {
            if (is_array($v)) {
                $fieldsPhp .= "                '$k' => [\n";
                foreach ($v as $kk => $vv) {
                    $vvStr = is_bool($vv) ? ($vv ? 'true' : 'false') : (is_numeric($vv) ? $vv : "'" . str_replace("'", "\\'", (string)$vv) . "'");
                    $kkStr = is_int($kk) ? "'" . $kk . "'" : "'" . $kk . "'";
                    $fieldsPhp .= "                    $kkStr => $vvStr,\n";
                }
                $fieldsPhp .= "                ],\n";
            } else {
                $vStr = is_bool($v) ? ($v ? 'true' : 'false') : (is_numeric($v) ? $v : "'" . str_replace("'", "\\'", (string)$v) . "'");
                $fieldsPhp .= "                '$k' => $vStr,\n";
            }
        }
        $fieldsPhp .= "            ],\n";
    }
    $fieldsPhp .= "        ]";

    // DataTable column edits (badges + selects translation)
    $dtEdits = '';
    $rawCols = [];
    foreach ($m['columns'] as $col) {
        if (count($col) >= 4 && $col[3] === true) {
            $dtEdits .= "->editColumn('{$col[0]}', fn (\$row) => \$row->{$col[0]} ? '<span class=\"badge bg-success\">' . e(__('common.active')) . '</span>' : '<span class=\"badge bg-secondary\">' . e(__('common.inactive')) . '</span>')\n            ";
            $rawCols[] = "'{$col[0]}'";
        }
    }
    $rawCols[] = "'actions'";
    $rawColsStr = implode(', ', $rawCols);

    // EXTRA methods (e.g. password hashing for users)
    $extra = '';
    if (!empty($m['hash_password'])) {
        $extra = "\n\n    protected function mapPayload(Request \$request): array\n    {\n        \$data = parent::mapPayload(\$request);\n        if (!empty(\$data['password'])) {\n            \$data['password'] = bcrypt(\$data['password']);\n        } else {\n            unset(\$data['password']);\n        }\n        return \$data;\n    }";
    }

    $controller = strtr($controllerTpl, [
        '__NS__' => $namespacePart,
        '__CLASS__' => $resource,
        '__MODEL__' => $modelClass,
        '__SLUG__' => $slug,
        '__SLUG_DOT__' => $slugUnder,                       // view path uses underscore
        '__SLUG_UNDER__' => $slugUnder,
        '__RULES__' => $rulesPhp,
        '__FIELDS_PHP__' => $fieldsPhp,
        '__DT_EDITS__' => $dtEdits,
        '__RAW_COLS__' => $rawColsStr,
        '__EXTRA_METHODS__' => $extra,
    ]);

    $ctrlDir = "$base/app/Http/Controllers/Admin/$namespacePart";
    @mkdir($ctrlDir, 0755, true);
    file_put_contents("$ctrlDir/{$resource}Controller.php", $controller);

    // index.blade.php
    $thead = '';
    $columns = '';
    foreach ($m['columns'] as $i => $col) {
        $width = $col[2] ?? null;
        $thead .= "    <th>{{ __('$slugUnder.{$col[1]}') }}</th>\n";
        $parts = ["data: '{$col[0]}'", "name: '{$col[0]}'"];
        if ($width) $parts[] = "width: '$width'";
        if (count($col) >= 4 && $col[3] === true) $parts[] = "searchable: false";
        $columns .= "    { " . implode(', ', $parts) . " },\n";
    }
    $indexBlade = strtr($indexTpl, [
        '__THEAD__' => rtrim($thead, "\n"),
        '__COLUMNS__' => $columns,
    ]);
    $viewDir = "$base/resources/views/admin/$slugUnder";
    @mkdir($viewDir, 0755, true);
    file_put_contents("$viewDir/index.blade.php", $indexBlade);

    // route file
    $routeFile = strtr($routeTpl, [
        '__NS__' => $namespacePart,
        '__CLASS__' => $resource,
        '__SLUG__' => $slug,
    ]);
    @mkdir("$base/routes/admin", 0755, true);
    file_put_contents("$base/routes/admin/$slug.php", $routeFile);

    // Lang files (en/km)
    foreach (['en' => $m['lang'], 'km' => $m['lang_km']] as $loc => $arr) {
        $langDir = "$base/lang/$loc";
        @mkdir($langDir, 0755, true);
        $php = "<?php\n\nreturn [\n";
        foreach ($arr as $k => $v) {
            $vEsc = str_replace("'", "\\'", $v);
            $php .= "    '$k' => '$vEsc',\n";
        }
        $php .= "];\n";
        file_put_contents("$langDir/$slugUnder.php", $php);
    }

    echo "wrote $slug ($resource)\n";
}

echo "done\n";
