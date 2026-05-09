<?php

use App\Http\Controllers\Admin\LoginHistorys\LoginHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('login-histories', [LoginHistoryController::class, 'index'])->name('login-histories.index');