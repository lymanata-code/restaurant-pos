<?php

use App\Http\Controllers\Admin\Printers\PrinterController;
use Illuminate\Support\Facades\Route;

Route::resource('printers', PrinterController::class)->except(['show']);