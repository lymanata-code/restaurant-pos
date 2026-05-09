<?php

namespace App\Http\Controllers\Admin\StockAdjustments;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\StockAdjustment;

class StockAdjustmentController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return StockAdjustment::class; }
    protected function viewPath(): string { return 'admin.stock_adjustments'; }
    protected function routeName(): string { return 'admin.stock-adjustments'; }
    protected function translationNamespace(): string { return 'stock_adjustments'; }
}