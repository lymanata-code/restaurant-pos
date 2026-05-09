<?php

use App\Http\Controllers\Admin\GoodsReceives\GoodsReceiveController;
use Illuminate\Support\Facades\Route;

Route::get('goods-receives', [GoodsReceiveController::class, 'index'])->name('goods-receives.index');