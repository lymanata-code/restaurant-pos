<?php

namespace App\Http\Controllers\Admin\CodeSequences;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\CodeSequence;

class CodeSequenceController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return CodeSequence::class; }
    protected function viewPath(): string { return 'admin.code_sequences'; }
    protected function routeName(): string { return 'admin.code-sequences'; }
    protected function translationNamespace(): string { return 'code_sequences'; }
}