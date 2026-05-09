<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Permission::class;
    }

    protected function viewPath(): string
    {
        return 'admin.permissions';
    }

    protected function routeName(): string
    {
        return 'admin.permissions';
    }

    protected function translationNamespace(): string
    {
        return 'permissions';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'module' => ['required','string','max:80'],
            'name' => ['required','string','max:255'],
            'slug' => ['required','string','max:255'],
            'description' => ['nullable','string'],
        ];
    }

    protected function fields(): array
    {
        return [
            'module' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 80,
                'col' => 'md-3',
            ],
            'name' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 255,
                'col' => 'md-5',
            ],
            'slug' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 255,
                'col' => 'md-4',
            ],
            'description' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
            ],
        ];
    }

    protected function configureDataTable($dt)
    {
        return $dt
            
            ->addColumn('actions', fn ($row) => view(
                'admin._partials.datatable_actions',
                ['row' => $row, 'routeName' => $this->routeName()]
            )->render())
            ->rawColumns(['actions']);
    }

}