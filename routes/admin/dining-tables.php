<?php

use App\Http\Controllers\Admin\DiningTables\DiningTableController;
use Illuminate\Support\Facades\Route;

Route::resource('dining-tables', DiningTableController::class)->except(['show']);