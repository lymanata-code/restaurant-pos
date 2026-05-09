<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'branches' => Restaurant::count(),
            'menu_items' => MenuItem::withoutGlobalScope('branch')->count(),
            'customers' => Customer::withoutGlobalScope('branch')->count(),
            'orders_today' => Order::withoutGlobalScope('branch')
                ->whereDate('created_at', today())->count(),
        ];
        return view('admin.dashboard.index', compact('stats'));
    }
}
