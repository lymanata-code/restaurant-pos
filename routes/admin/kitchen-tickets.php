<?php

use App\Http\Controllers\Admin\KitchenTickets\KitchenTicketController;
use Illuminate\Support\Facades\Route;

Route::get('kitchen-tickets', [KitchenTicketController::class, 'index'])->name('kitchen-tickets.index');