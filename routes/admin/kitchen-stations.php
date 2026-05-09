<?php

use App\Http\Controllers\Admin\KitchenStations\KitchenStationController;
use Illuminate\Support\Facades\Route;

Route::get('kitchen-stations', [KitchenStationController::class, 'index'])->name('kitchen-stations.index');