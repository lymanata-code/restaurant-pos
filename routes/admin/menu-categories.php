<?php

use App\Http\Controllers\Admin\MenuCategorys\MenuCategoryController;
use Illuminate\Support\Facades\Route;

Route::resource('menu-categories', MenuCategoryController::class)->except(['show']);