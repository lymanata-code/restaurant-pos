<?php

use App\Http\Controllers\Admin\JournalEntrys\JournalEntryController;
use Illuminate\Support\Facades\Route;

Route::get('journal-entries', [JournalEntryController::class, 'index'])->name('journal-entries.index');