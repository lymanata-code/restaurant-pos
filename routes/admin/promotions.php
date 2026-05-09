<?php

use App\Http\Controllers\Admin\Promotions\PromotionController;
use Illuminate\Support\Facades\Route;

Route::resource('promotions', PromotionController::class)->except(['show']);