<?php

use App\Http\Controllers\Admin\MenuItems\MenuItemController;
use Illuminate\Support\Facades\Route;

Route::resource('menu-items', MenuItemController::class)->except(['show']);