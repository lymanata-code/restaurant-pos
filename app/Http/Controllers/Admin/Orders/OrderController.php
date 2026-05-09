<?php

namespace App\Http\Controllers\Admin\Orders;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\Order;

class OrderController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return Order::class; }
    protected function viewPath(): string { return 'admin.orders'; }
    protected function routeName(): string { return 'admin.orders'; }
    protected function translationNamespace(): string { return 'orders'; }
}