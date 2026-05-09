<?php

use App\Http\Controllers\Admin\Modifiers\ModifierController;
use Illuminate\Support\Facades\Route;

Route::get('modifiers', [ModifierController::class, 'index'])->name('modifiers.index');