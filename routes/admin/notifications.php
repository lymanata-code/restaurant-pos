<?php

use App\Http\Controllers\Admin\Notifications\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');