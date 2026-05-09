<?php

use App\Http\Controllers\Admin\Expenses\ExpenseController;
use Illuminate\Support\Facades\Route;

Route::resource('expenses', ExpenseController::class)->except(['show']);