<?php

namespace App\Http\Controllers\Admin\PurchaseOrders;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\PurchaseOrder;

class PurchaseOrderController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return PurchaseOrder::class; }
    protected function viewPath(): string { return 'admin.purchase_orders'; }
    protected function routeName(): string { return 'admin.purchase-orders'; }
    protected function translationNamespace(): string { return 'purchase_orders'; }
}