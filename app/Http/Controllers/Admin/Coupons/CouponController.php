<?php

namespace App\Http\Controllers\Admin\Coupons;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\Coupon;

class CouponController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return Coupon::class; }
    protected function viewPath(): string { return 'admin.coupons'; }
    protected function routeName(): string { return 'admin.coupons'; }
    protected function translationNamespace(): string { return 'coupons'; }
}