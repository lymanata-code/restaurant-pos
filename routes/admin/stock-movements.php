<?php

use App\Http\Controllers\Admin\StockMovements\StockMovementController;
use Illuminate\Support\Facades\Route;

Route::get('stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');