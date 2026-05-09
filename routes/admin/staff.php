<?php

use App\Http\Controllers\Admin\Staffs\StaffController;
use Illuminate\Support\Facades\Route;

Route::resource('staff', StaffController::class)->except(['show']);