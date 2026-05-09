<?php

namespace App\Http\Controllers\Admin\GoodsReceives;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\GoodsReceive;

class GoodsReceiveController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return GoodsReceive::class; }
    protected function viewPath(): string { return 'admin.goods_receives'; }
    protected function routeName(): string { return 'admin.goods-receives'; }
    protected function translationNamespace(): string { return 'goods_receives'; }
}