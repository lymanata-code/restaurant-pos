<?php

use App\Http\Controllers\Admin\ModifierGroups\ModifierGroupController;
use Illuminate\Support\Facades\Route;

Route::get('modifier-groups', [ModifierGroupController::class, 'index'])->name('modifier-groups.index');