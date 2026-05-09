<?php

use App\Http\Controllers\Admin\Customers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::resource('customers', CustomerController::class)->except(['show']);