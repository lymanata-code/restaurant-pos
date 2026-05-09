<?php

use App\Http\Controllers\Admin\Accounts\AccountController;
use Illuminate\Support\Facades\Route;

Route::resource('accounts', AccountController::class)->except(['show']);