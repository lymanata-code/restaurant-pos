<?php

use App\Http\Controllers\Admin\Coupons\CouponController;
use Illuminate\Support\Facades\Route;

Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');