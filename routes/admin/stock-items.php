<?php

use App\Http\Controllers\Admin\StockItems\StockItemController;
use Illuminate\Support\Facades\Route;

Route::resource('stock-items', StockItemController::class)->except(['show']);