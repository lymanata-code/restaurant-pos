<?php

use App\Http\Controllers\Admin\Roles\RoleController;
use Illuminate\Support\Facades\Route;

Route::resource('roles', RoleController::class)->except(['show']);