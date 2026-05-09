<?php

use App\Http\Controllers\Admin\PaymentMethods\PaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::resource('payment-methods', PaymentMethodController::class)->except(['show']);