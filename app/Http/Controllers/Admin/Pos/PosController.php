<?php

namespace App\Http\Controllers\Admin\Pos;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DiningTable;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * POS Terminal — classic two-pane register UI:
     *   left:  customer/price-option toolbar -> search -> cart line-items -> totals
     *   right: tabs (Category / Featured / All) over a product grid
     *   bottom: payment / draft action bar
     *
     * Server side just provides the data; the cart + checkout flow is wired
     * client-side as a Vue island so the heavy interaction (add line, change
     * qty, discount, tax, change tab, filter by category) doesn't round-trip.
     * Submitting the order to the backend is intentionally deferred — Phase 2.
     */
    public function terminal(): View
    {
        $categories = MenuCategory::withCount('items')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Featured = combo items + top-of-list per category (simple, deterministic).
        $featuredItems = MenuItem::query()
            ->where('is_available', true)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->where('is_combo', true)
                    ->orWhere('sort_order', '>=', 1);
            })
            ->orderByDesc('is_combo')
            ->orderBy('sort_order')
            ->limit(24)
            ->get();

        $items = MenuItem::query()
            ->with('category:id,name')
            ->where('is_available', true)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(48)
            ->get();

        $customers = Customer::query()
            ->where('status', 'active')
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name', 'customer_code', 'phone']);

        $tables = DiningTable::query()
            ->where('is_active', true)
            ->orderBy('table_no')
            ->limit(200)
            ->get(['id', 'table_no', 'capacity', 'status']);

        $priceOptions = config('pos.price_options', [
            'retail' => 'Retail',
            'member' => 'Member',
            'wholesale' => 'Wholesale',
        ]);

        $openStatuses = ['pending', 'in_kitchen', 'ready', 'served'];

        $stats = [
            'orders_today' => Order::withoutGlobalScope('branch')->whereDate('created_at', today())->count(),
            'open_tabs'    => Order::withoutGlobalScope('branch')->whereIn('status', $openStatuses)->count(),
        ];

        return view('admin.pos.terminal', compact(
            'categories', 'featuredItems', 'items', 'customers', 'tables', 'priceOptions', 'stats'
        ));
    }

    /**
     * Cashier — close out tabs / take payment.
     *
     * Phase 1 placeholder: lists today's open orders that still need to be
     * paid, with links into the full Orders module.
     */
    public function register(): View
    {
        $openStatuses = ['pending', 'in_kitchen', 'ready', 'served'];

        $openOrders = Order::withoutGlobalScope('branch')
            ->with('table')
            ->whereIn('status', $openStatuses)
            ->latest('created_at')
            ->take(20)
            ->get();

        return view('admin.pos.register', compact('openOrders'));
    }
}
