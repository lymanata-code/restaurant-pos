<?php

use App\Http\Controllers\Admin\Pos\PosController;
use Illuminate\Support\Facades\Route;

Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('terminal', [PosController::class, 'terminal'])->name('terminal');
    Route::get('register', [PosController::class, 'register'])->name('register');
});
