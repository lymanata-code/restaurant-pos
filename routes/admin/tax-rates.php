<?php

use App\Http\Controllers\Admin\TaxRates\TaxRateController;
use Illuminate\Support\Facades\Route;

Route::resource('tax-rates', TaxRateController::class)->except(['show']);