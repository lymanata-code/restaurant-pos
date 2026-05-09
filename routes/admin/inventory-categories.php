<?php

use App\Http\Controllers\Admin\InventoryCategorys\InventoryCategoryController;
use Illuminate\Support\Facades\Route;

Route::resource('inventory-categories', InventoryCategoryController::class)->except(['show']);