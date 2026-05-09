<?php

namespace App\Http\Controllers\Admin\Modifiers;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\Modifier;

class ModifierController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return Modifier::class; }
    protected function viewPath(): string { return 'admin.modifiers'; }
    protected function routeName(): string { return 'admin.modifiers'; }
    protected function translationNamespace(): string { return 'modifiers'; }
}