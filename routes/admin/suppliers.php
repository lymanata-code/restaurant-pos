<?php

use App\Http\Controllers\Admin\Suppliers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::resource('suppliers', SupplierController::class)->except(['show']);