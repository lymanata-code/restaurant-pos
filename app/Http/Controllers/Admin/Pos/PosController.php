<?php

namespace App\Http\Controllers\Admin\Pos;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use App\Models\MenuCategory;
use App\Models\Order;
use Illuminate\View\View;

class PosController extends Controller
{
    /**
     * POS Terminal — entry point for the waiter / cashier ordering UI.
     *
     * Phase 1 ships the navigation shell only: tables grid, today's stats,
     * and the menu category breakdown. The full ticket-entry interaction
     * (add item, modifier picker, payment, send-to-kitchen) is intentionally
     * out of scope of the initial scaffold — see PR #1 description.
     */
    public function terminal(): View
    {
        $tables = DiningTable::orderBy('table_no')->take(24)->get();

        $categories = MenuCategory::withCount('items')->orderBy('sort_order')->get();

        $openStatuses = ['pending', 'in_kitchen', 'ready', 'served'];

        $stats = [
            'orders_today' => Order::withoutGlobalScope('branch')->whereDate('created_at', today())->count(),
            'open_tabs'    => Order::withoutGlobalScope('branch')->whereIn('status', $openStatuses)->count(),
            'tables_total' => DiningTable::count(),
        ];

        return view('admin.pos.terminal', compact('tables', 'categories', 'stats'));
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
