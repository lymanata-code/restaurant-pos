<?php

namespace App\Http\Controllers\Admin\ModifierGroups;

use App\Http\Controllers\Admin\AbstractReadOnlyController;
use App\Models\ModifierGroup;

class ModifierGroupController extends AbstractReadOnlyController
{
    protected function modelClass(): string { return ModifierGroup::class; }
    protected function viewPath(): string { return 'admin.modifier_groups'; }
    protected function routeName(): string { return 'admin.modifier-groups'; }
    protected function translationNamespace(): string { return 'modifier_groups'; }
}