<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| All-in-One Restaurant POS Management System Migration
|--------------------------------------------------------------------------
| Recommended stack: PHP 8.4, Laravel, MySQL 8.4, InnoDB, utf8mb4.
|
| This single migration creates the core schema for:
| Dashboard, Menu & Category, Table/Customer, Orders, Kitchen/KDS,
| Payments/Billing, Promotions, Inventory/Recipe/BOM, Purchasing/Suppliers,
| Staff/RBAC, Accounting/Finance, Reports, Notifications, Online Ordering,
| Security/Audit Log, and System Settings.
|
| Suggested filename:
| 2026_05_09_000000_create_restaurant_pos_all_in_one_schema.php
*/

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        /*
        |--------------------------------------------------------------------------
        | System / Organization Settings
        |--------------------------------------------------------------------------
        */

        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('logo_path')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->string('tax_number', 100)->nullable();
            $table->text('receipt_header')->nullable();
            $table->text('receipt_footer')->nullable();
            $table->text('refund_policy')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('group', 100)->index();
            $table->string('key', 150)->index();
            $table->longText('value')->nullable();
            $table->string('value_type', 50)->default('string'); // string, number, boolean, json, text
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->unique(['restaurant_id', 'group', 'key'], 'settings_restaurant_group_key_unique');
        });

        Schema::create('code_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('sequence_type', 80); // customer, table, order, kot, invoice, receipt, po, grn
            $table->string('prefix', 50)->nullable();
            $table->string('date_format', 50)->nullable(); // Ymd, Ym, etc.
            $table->unsignedBigInteger('next_number')->default(1);
            $table->unsignedInteger('padding')->default(5);
            $table->string('suffix', 50)->nullable();
            $table->boolean('reset_daily')->default(false);
            $table->timestamps();
            $table->unique(['restaurant_id', 'sequence_type'], 'code_sequences_unique');
        });

        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('code', 50);
            $table->string('name');
            $table->enum('type', ['cash', 'aba', 'khqr', 'wing', 'bank_transfer', 'card', 'other'])->default('other');
            $table->boolean('requires_reference')->default(false);
            $table->boolean('is_online')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('gateway_config')->nullable();
            $table->timestamps();
            $table->unique(['restaurant_id', 'code'], 'payment_methods_unique_code');
        });

        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name');
            $table->string('printer_type', 50)->default('network'); // network, usb, bluetooth
            $table->string('ip_address', 100)->nullable();
            $table->unsignedInteger('port')->nullable();
            $table->string('paper_size', 50)->default('80mm');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('print_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('template_type', 50); // invoice, receipt, kot, z_report, stock_report
            $table->string('name');
            $table->longText('content')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Staff / Users / RBAC
        |--------------------------------------------------------------------------
        */

        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('staff_code', 50)->unique();
            $table->string('name');
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable()->index();
            $table->string('address')->nullable();
            $table->string('position')->nullable();
            $table->date('hire_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->string('name');
            $table->string('username', 100)->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone', 50)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->enum('status', ['active', 'inactive', 'locked'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name'); // Super Admin, Manager, Cashier, Accountant, Waiter, Kitchen Staff
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
            $table->unique(['restaurant_id', 'slug'], 'roles_unique_slug');
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module', 80)->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'role_id']);
        });

        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('username', 100)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->boolean('success')->default(false);
            $table->text('failure_reason')->nullable();
            $table->timestamp('logged_in_at')->useCurrent();
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Menu / Category / Modifiers / Combo
        |--------------------------------------------------------------------------
        */

        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('menu_categories')->nullOnDelete();
            $table->string('code', 50)->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name');
            $table->decimal('rate', 8, 4)->default(0);
            $table->enum('type', ['tax', 'service_charge'])->default('tax');
            $table->boolean('is_inclusive')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('menu_categories')->nullOnDelete();
            $table->foreignId('tax_rate_id')->nullable()->constrained('tax_rates')->nullOnDelete();
            $table->string('item_code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('sale_price', 14, 2)->default(0);
            $table->decimal('cost_price', 14, 2)->default(0);
            $table->boolean('track_inventory')->default(false);
            $table->boolean('is_combo')->default(false);
            $table->boolean('is_available')->default(true);
            $table->enum('status', ['active', 'sold_out', 'inactive'])->default('active');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'status']);
        });

        Schema::create('menu_item_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->string('size_name', 80)->nullable(); // S, M, L
            $table->string('unit_name', 80)->nullable();
            $table->decimal('price', 14, 2)->default(0);
            $table->decimal('cost_price', 14, 2)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('modifier_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name'); // Size, Sugar Level, Spicy Level, Topping
            $table->boolean('is_required')->default(false);
            $table->unsignedInteger('min_select')->default(0);
            $table->unsignedInteger('max_select')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modifier_group_id')->constrained('modifier_groups')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('extra_price', 14, 2)->default(0);
            $table->decimal('extra_cost', 14, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_item_modifier_group', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('modifier_group_id')->constrained('modifier_groups')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['menu_item_id', 'modifier_group_id'], 'item_modifier_group_unique');
        });

        Schema::create('combo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('child_menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->decimal('quantity', 12, 3)->default(1);
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Table / Zone / Customer
        |--------------------------------------------------------------------------
        */

        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('dining_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->string('table_code', 50)->unique();
            $table->string('table_no', 50);
            $table->unsignedInteger('capacity')->default(1);
            $table->enum('status', ['available', 'occupied', 'reserved', 'inactive'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('customer_code', 50)->unique();
            $table->string('name');
            $table->string('phone', 50)->nullable()->index();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('membership_no', 80)->nullable()->index();
            $table->unsignedInteger('points')->default(0);
            $table->decimal('credit_limit', 14, 2)->default(0);
            $table->decimal('outstanding_balance', 14, 2)->default(0);
            $table->boolean('is_blacklisted')->default(false);
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('label', 100)->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone', 50)->nullable();
            $table->text('address');
            $table->string('city', 100)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('table_moves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_table_id')->nullable()->constrained('dining_tables')->nullOnDelete();
            $table->foreignId('to_table_id')->nullable()->constrained('dining_tables')->nullOnDelete();
            $table->foreignId('moved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->timestamp('moved_at')->useCurrent();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Kitchen / KDS
        |--------------------------------------------------------------------------
        */

        Schema::create('kitchen_stations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('printer_id')->nullable()->constrained('printers')->nullOnDelete();
            $table->string('name'); // Grill, Fry, Soup, Drink, Dessert
            $table->text('description')->nullable();
            $table->boolean('auto_print_kot')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('kitchen_station_menu_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitchen_station_id')->constrained('kitchen_stations')->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['kitchen_station_id', 'menu_item_id'], 'station_menu_item_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | Order Management
        |--------------------------------------------------------------------------
        */

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('order_no', 80)->unique();
            $table->enum('order_type', ['dine_in', 'takeaway', 'delivery', 'qr_self_order'])->default('dine_in');
            $table->foreignId('table_id')->nullable()->constrained('dining_tables')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('customer_address_id')->nullable()->constrained('customer_addresses')->nullOnDelete();
            $table->foreignId('waiter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cashier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'pending', 'in_kitchen', 'ready', 'served', 'paid', 'closed', 'cancelled', 'void'])->default('pending');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('service_charge_amount', 14, 2)->default(0);
            $table->decimal('delivery_fee', 14, 2)->default(0);
            $table->decimal('tip_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->decimal('balance_amount', 14, 2)->default(0);
            $table->boolean('is_split_bill')->default(false);
            $table->text('notes')->nullable();
            $table->timestamp('sent_to_kitchen_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('cancel_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['order_type', 'status']);
            $table->index(['created_at', 'status']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained('menu_items')->nullOnDelete();
            $table->foreignId('menu_item_price_id')->nullable()->constrained('menu_item_prices')->nullOnDelete();
            $table->foreignId('kitchen_station_id')->nullable()->constrained('kitchen_stations')->nullOnDelete();
            $table->string('item_name');
            $table->string('size_name', 80)->nullable();
            $table->decimal('quantity', 12, 3)->default(1);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->decimal('modifier_total', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->enum('status', ['pending', 'in_kitchen', 'ready', 'served', 'cancelled', 'void'])->default('pending');
            $table->text('special_note')->nullable();
            $table->timestamp('sent_to_kitchen_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['order_id', 'status']);
        });

        Schema::create('order_item_modifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('modifier_id')->nullable()->constrained('modifiers')->nullOnDelete();
            $table->string('modifier_group_name')->nullable();
            $table->string('modifier_name');
            $table->decimal('extra_price', 14, 2)->default(0);
            $table->decimal('extra_cost', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->cascadeOnDelete();
            $table->string('from_status', 50)->nullable();
            $table->string('to_status', 50);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('kitchen_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('kot_no', 80)->unique();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('kitchen_station_id')->nullable()->constrained('kitchen_stations')->nullOnDelete();
            $table->enum('status', ['pending', 'printed', 'in_kitchen', 'ready', 'cancelled'])->default('pending');
            $table->timestamp('printed_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamps();
        });

        Schema::create('kitchen_ticket_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kitchen_ticket_id')->constrained('kitchen_tickets')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('order_items')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['kitchen_ticket_id', 'order_item_id'], 'kot_order_item_unique');
        });

        /*
        |--------------------------------------------------------------------------
        | Promotions / Discounts / Coupons
        |--------------------------------------------------------------------------
        */

        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name');
            $table->enum('promotion_type', ['bill_discount', 'item_discount', 'coupon', 'buy_x_get_y', 'happy_hour'])->default('bill_discount');
            $table->enum('discount_type', ['percent', 'fixed', 'free_item'])->default('percent');
            $table->decimal('discount_value', 14, 2)->default(0);
            $table->decimal('max_discount_amount', 14, 2)->nullable();
            $table->decimal('min_spend', 14, 2)->default(0);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('days_of_week')->nullable();
            $table->boolean('requires_manager_approval')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promotion_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete();
            $table->nullableMorphs('target'); // menu item/category/all bill target
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete();
            $table->string('code')->unique();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->unsignedInteger('usage_limit_per_customer')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('order_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->cascadeOnDelete();
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->string('discount_name');
            $table->enum('discount_type', ['percent', 'fixed', 'manual'])->default('manual');
            $table->decimal('discount_value', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('applied_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Billing / Payment / Cash Drawer
        |--------------------------------------------------------------------------
        */

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('invoice_no', 80)->unique();
            $table->string('receipt_no', 80)->nullable()->unique();
            $table->enum('invoice_type', ['invoice', 'receipt'])->default('receipt');
            $table->enum('status', ['draft', 'issued', 'paid', 'void', 'refunded'])->default('issued');
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('service_charge_amount', 14, 2)->default(0);
            $table->decimal('tip_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->timestamp('issued_at')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cash_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('cashier_id')->constrained('users')->cascadeOnDelete();
            $table->string('shift_no', 80)->unique();
            $table->decimal('start_cash', 14, 2)->default(0);
            $table->decimal('expected_cash', 14, 2)->default(0);
            $table->decimal('counted_cash', 14, 2)->nullable();
            $table->decimal('short_over_amount', 14, 2)->default(0);
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('cash_shift_id')->nullable()->constrained('cash_shifts')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_no', 80)->unique();
            $table->enum('payment_type', ['full', 'partial', 'on_account', 'supplier_payment', 'refund'])->default('full');
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('received_amount', 14, 2)->nullable();
            $table->decimal('change_amount', 14, 2)->default(0);
            $table->string('reference_no')->nullable();
            $table->enum('status', ['pending', 'success', 'failed', 'void', 'refunded'])->default('success');
            $table->foreignId('paid_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cash_drawer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_shift_id')->constrained('cash_shifts')->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('transaction_type', ['open_shift', 'cash_in', 'cash_out', 'payment', 'refund', 'close_shift'])->default('payment');
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->string('refund_no', 80)->unique();
            $table->decimal('amount', 14, 2)->default(0);
            $table->text('reason')->nullable();
            $table->enum('status', ['requested', 'approved', 'rejected', 'paid'])->default('requested');
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->enum('entry_type', ['debit', 'credit'])->default('debit');
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Inventory / Ingredients / Recipe BOM
        |--------------------------------------------------------------------------
        */

        Schema::create('inventory_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol', 20);
            $table->string('unit_type', 50)->nullable(); // weight, volume, piece
            $table->boolean('is_base')->default(false);
            $table->timestamps();
        });

        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_unit_id')->constrained('units')->cascadeOnDelete();
            $table->foreignId('to_unit_id')->constrained('units')->cascadeOnDelete();
            $table->decimal('factor', 18, 6)->default(1);
            $table->timestamps();
            $table->unique(['from_unit_id', 'to_unit_id']);
        });

        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('inventory_category_id')->nullable()->constrained('inventory_categories')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->string('item_code', 80)->unique();
            $table->string('name');
            $table->decimal('purchase_price', 14, 2)->default(0);
            $table->decimal('quantity_on_hand', 18, 4)->default(0);
            $table->decimal('reorder_level', 18, 4)->default(0);
            $table->decimal('reorder_quantity', 18, 4)->default(0);
            $table->date('expiry_date')->nullable();
            $table->boolean('is_ingredient')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('quantity_required', 18, 4)->default(0);
            $table->decimal('wastage_percent', 8, 4)->default(0);
            $table->timestamps();
            $table->unique(['menu_item_id', 'stock_item_id'], 'recipe_item_unique');
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->nullableMorphs('reference'); // order, goods receive, adjustment
            $table->enum('movement_type', ['stock_in', 'stock_out', 'adjustment_in', 'adjustment_out', 'sale_deduct', 'return_in'])->default('stock_in');
            $table->decimal('quantity', 18, 4)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('balance_after', 18, 4)->default(0);
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['movement_type', 'created_at']);
        });

        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_no', 80)->unique();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->enum('status', ['draft', 'approved', 'cancelled'])->default('draft');
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('stock_adjustment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_adjustment_id')->constrained('stock_adjustments')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('system_quantity', 18, 4)->default(0);
            $table->decimal('counted_quantity', 18, 4)->default(0);
            $table->decimal('difference_quantity', 18, 4)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Purchasing / Suppliers / Accounts Payable
        |--------------------------------------------------------------------------
        */

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('supplier_code', 80)->unique();
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('outstanding_balance', 14, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->string('po_no', 80)->unique();
            $table->enum('status', ['draft', 'approved', 'sent', 'partially_received', 'received', 'cancelled'])->default('draft');
            $table->date('order_date')->nullable();
            $table->date('expected_date')->nullable();
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_amount', 14, 2)->default(0);
            $table->decimal('tax_amount', 14, 2)->default(0);
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('quantity', 18, 4)->default(0);
            $table->decimal('received_quantity', 18, 4)->default(0);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('goods_receives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            $table->string('grn_no', 80)->unique();
            $table->enum('status', ['pending', 'received', 'partially_received', 'cancelled'])->default('pending');
            $table->date('received_date')->nullable();
            $table->decimal('total_amount', 14, 2)->default(0);
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('goods_receive_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receive_id')->constrained('goods_receives')->cascadeOnDelete();
            $table->foreignId('purchase_order_item_id')->nullable()->constrained('purchase_order_items')->nullOnDelete();
            $table->foreignId('stock_item_id')->constrained('stock_items')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->decimal('quantity_received', 18, 4)->default(0);
            $table->decimal('unit_cost', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2)->default(0);
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->nullOnDelete();
            $table->foreignId('goods_receive_id')->nullable()->constrained('goods_receives')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->enum('entry_type', ['debit', 'credit'])->default('credit');
            $table->decimal('amount', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Accounting / Finance
        |--------------------------------------------------------------------------
        */

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code', 50)->unique();
            $table->string('name');
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense', 'cost_of_goods_sold'])->default('expense');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('expense_no', 80)->unique();
            $table->string('title');
            $table->decimal('amount', 14, 2)->default(0);
            $table->date('expense_date')->nullable();
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('journal_no', 80)->unique();
            $table->date('journal_date');
            $table->nullableMorphs('reference');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained('journal_entries')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->decimal('debit', 14, 2)->default(0);
            $table->decimal('credit', 14, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Online Ordering / Delivery / QR Self Order
        |--------------------------------------------------------------------------
        */

        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('table_id')->nullable()->constrained('dining_tables')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->string('name');
            $table->string('contact_phone', 50)->nullable();
            $table->json('integration_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('delivery_partner_id')->nullable()->constrained('delivery_partners')->nullOnDelete();
            $table->foreignId('driver_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'preparing', 'on_the_way', 'delivered', 'rejected', 'cancelled'])->default('pending');
            $table->decimal('delivery_fee', 14, 2)->default(0);
            $table->string('tracking_no')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->text('delivery_note')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Notifications / Reports / Audit / Backup
        |--------------------------------------------------------------------------
        */

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('message')->nullable();
            $table->enum('notification_type', ['order_new', 'kitchen_ready', 'low_stock', 'shift_close', 'shortage', 'payment_success', 'refund', 'system'])->default('system');
            $table->enum('channel', ['system', 'popup', 'sound', 'sms', 'email', 'telegram', 'whatsapp'])->default('system');
            $table->nullableMorphs('reference');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('report_type', 100); // sales, finance, operation, inventory
            $table->enum('format', ['pdf', 'excel', 'csv', 'word', 'print'])->default('pdf');
            $table->json('filters')->nullable();
            $table->string('file_path')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('module', 100)->index();
            $table->string('action', 100)->index(); // create, update, delete, void, refund, login
            $table->nullableMorphs('auditable');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('reason')->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
            $table->index(['module', 'action', 'performed_at']);
        });

        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->nullableMorphs('uploadable');
            $table->string('disk', 80)->default('public');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->timestamps();
        });

        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('backup_no', 80)->unique();
            $table->enum('backup_type', ['manual', 'auto', 'cloud'])->default('manual');
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('size_bytes')->default(0);
            $table->enum('status', ['pending', 'completed', 'failed', 'restored'])->default('pending');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | Default Seed Data
        |--------------------------------------------------------------------------
        */

        DB::table('roles')->insert([
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Full system access', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manager', 'slug' => 'manager', 'description' => 'Manage menu, stock, purchasing, approvals and reports', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cashier', 'slug' => 'cashier', 'description' => 'Create/edit orders, receive payment and manage shifts', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accountant', 'slug' => 'accountant', 'description' => 'Manage payments, income, expenses and financial reports', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Waiter / Server', 'slug' => 'waiter-server', 'description' => 'Take orders, send to kitchen and mark served', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kitchen Staff', 'slug' => 'kitchen-staff', 'description' => 'View kitchen queue and update KDS status', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('permissions')->insert([
            ['module' => 'dashboard', 'name' => 'View Dashboard', 'slug' => 'dashboard.view', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'menu', 'name' => 'Manage Menu', 'slug' => 'menu.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'table', 'name' => 'Manage Tables', 'slug' => 'table.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'customer', 'name' => 'Manage Customers', 'slug' => 'customer.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'order', 'name' => 'Create/Edit Orders', 'slug' => 'order.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'order', 'name' => 'Void Orders', 'slug' => 'order.void', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'kitchen', 'name' => 'Manage Kitchen Queue', 'slug' => 'kitchen.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'payment', 'name' => 'Receive Payments', 'slug' => 'payment.receive', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'payment', 'name' => 'Refund Payments', 'slug' => 'payment.refund', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'promotion', 'name' => 'Manage Promotions', 'slug' => 'promotion.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'inventory', 'name' => 'Manage Inventory', 'slug' => 'inventory.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'purchasing', 'name' => 'Manage Purchasing', 'slug' => 'purchasing.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'staff', 'name' => 'Manage Staff and Users', 'slug' => 'staff.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'accounting', 'name' => 'Manage Accounting', 'slug' => 'accounting.manage', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'report', 'name' => 'Access Reports', 'slug' => 'report.view', 'created_at' => now(), 'updated_at' => now()],
            ['module' => 'setting', 'name' => 'Manage System Settings', 'slug' => 'setting.manage', 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('payment_methods')->insert([
            ['code' => 'CASH', 'name' => 'Cash', 'type' => 'cash', 'requires_reference' => false, 'is_online' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'ABA', 'name' => 'ABA', 'type' => 'aba', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'KHQR', 'name' => 'KHQR', 'type' => 'khqr', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'WING', 'name' => 'Wing', 'type' => 'wing', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'BANK_TRANSFER', 'name' => 'Bank Transfer', 'type' => 'bank_transfer', 'requires_reference' => true, 'is_online' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'CARD', 'name' => 'Card', 'type' => 'card', 'requires_reference' => true, 'is_online' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('units')->insert([
            ['name' => 'Kilogram', 'symbol' => 'kg', 'unit_type' => 'weight', 'is_base' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gram', 'symbol' => 'g', 'unit_type' => 'weight', 'is_base' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Liter', 'symbol' => 'l', 'unit_type' => 'volume', 'is_base' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Milliliter', 'symbol' => 'ml', 'unit_type' => 'volume', 'is_base' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Piece', 'symbol' => 'pcs', 'unit_type' => 'piece', 'is_base' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $tables = [
            'backups',
            'file_uploads',
            'audit_logs',
            'report_exports',
            'notifications',
            'delivery_orders',
            'delivery_partners',
            'qr_codes',
            'journal_entry_lines',
            'journal_entries',
            'expenses',
            'accounts',
            'supplier_accounts',
            'goods_receive_items',
            'goods_receives',
            'purchase_order_items',
            'purchase_orders',
            'suppliers',
            'stock_adjustment_items',
            'stock_adjustments',
            'stock_movements',
            'recipes',
            'stock_items',
            'unit_conversions',
            'units',
            'inventory_categories',
            'customer_accounts',
            'refunds',
            'cash_drawer_transactions',
            'payments',
            'cash_shifts',
            'invoices',
            'order_discounts',
            'coupons',
            'promotion_targets',
            'promotions',
            'kitchen_ticket_items',
            'kitchen_tickets',
            'order_status_histories',
            'order_item_modifiers',
            'order_items',
            'orders',
            'kitchen_station_menu_item',
            'kitchen_stations',
            'table_moves',
            'customer_addresses',
            'customers',
            'dining_tables',
            'zones',
            'combo_items',
            'menu_item_modifier_group',
            'modifiers',
            'modifier_groups',
            'menu_item_prices',
            'menu_items',
            'tax_rates',
            'menu_categories',
            'login_histories',
            'user_role',
            'role_permission',
            'permissions',
            'roles',
            'users',
            'staff',
            'print_templates',
            'printers',
            'payment_methods',
            'code_sequences',
            'system_settings',
            'restaurants',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
