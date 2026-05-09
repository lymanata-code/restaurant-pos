<?php

use App\Http\Controllers\Admin\PurchaseOrders\PurchaseOrderController;
use Illuminate\Support\Facades\Route;

Route::get('purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');