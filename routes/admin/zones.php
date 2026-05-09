<?php

use App\Http\Controllers\Admin\Zones\ZoneController;
use Illuminate\Support\Facades\Route;

Route::resource('zones', ZoneController::class)->except(['show']);