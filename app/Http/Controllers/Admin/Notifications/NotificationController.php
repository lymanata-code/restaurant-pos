<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\Notification;

class NotificationController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return Notification::class; }
    protected function viewPath(): string { return 'admin.notifications'; }
    protected function routeName(): string { return 'admin.notifications'; }
    protected function translationNamespace(): string { return 'notifications'; }
}