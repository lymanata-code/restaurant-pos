<?php

namespace App\Http\Controllers\Admin\LoginHistorys;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\LoginHistory;

class LoginHistoryController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return LoginHistory::class; }
    protected function viewPath(): string { return 'admin.login_histories'; }
    protected function routeName(): string { return 'admin.login-histories'; }
    protected function translationNamespace(): string { return 'login_histories'; }
}