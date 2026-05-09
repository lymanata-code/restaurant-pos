<?php

use App\Http\Controllers\Admin\Permissions\PermissionController;
use Illuminate\Support\Facades\Route;

Route::resource('permissions', PermissionController::class)->except(['show']);