<?php

use App\Http\Controllers\Admin\Branches\BranchController;
use Illuminate\Support\Facades\Route;

Route::resource('branches', BranchController::class)->except(['show']);
