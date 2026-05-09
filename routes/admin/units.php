<?php

use App\Http\Controllers\Admin\Units\UnitController;
use Illuminate\Support\Facades\Route;

Route::resource('units', UnitController::class)->except(['show']);