<?php

namespace App\Http\Controllers\Admin\StockMovements;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\StockMovement;

class StockMovementController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return StockMovement::class; }
    protected function viewPath(): string { return 'admin.stock_movements'; }
    protected function routeName(): string { return 'admin.stock-movements'; }
    protected function translationNamespace(): string { return 'stock_movements'; }
}