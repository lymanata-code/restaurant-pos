<?php

use App\Http\Controllers\Admin\StockAdjustments\StockAdjustmentController;
use Illuminate\Support\Facades\Route;

Route::get('stock-adjustments', [StockAdjustmentController::class, 'index'])->name('stock-adjustments.index');