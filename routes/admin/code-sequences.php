<?php

use App\Http\Controllers\Admin\CodeSequences\CodeSequenceController;
use Illuminate\Support\Facades\Route;

Route::get('code-sequences', [CodeSequenceController::class, 'index'])->name('code-sequences.index');