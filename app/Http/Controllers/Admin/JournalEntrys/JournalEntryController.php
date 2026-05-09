<?php

namespace App\Http\Controllers\Admin\JournalEntrys;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\JournalEntry;

class JournalEntryController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return JournalEntry::class; }
    protected function viewPath(): string { return 'admin.journal_entries'; }
    protected function routeName(): string { return 'admin.journal-entries'; }
    protected function translationNamespace(): string { return 'journal_entries'; }
}