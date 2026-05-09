<?php

namespace App\Http\Controllers\Admin\KitchenStations;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\KitchenStation;

class KitchenStationController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return KitchenStation::class; }
    protected function viewPath(): string { return 'admin.kitchen_stations'; }
    protected function routeName(): string { return 'admin.kitchen-stations'; }
    protected function translationNamespace(): string { return 'kitchen_stations'; }
}