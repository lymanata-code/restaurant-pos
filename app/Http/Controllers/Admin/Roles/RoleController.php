<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Role::class;
    }

    protected function viewPath(): string
    {
        return 'admin.roles';
    }

    protected function routeName(): string
    {
        return 'admin.roles';
    }

    protected function translationNamespace(): string
    {
        return 'roles';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'slug' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'is_system' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'name' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 255,
                'col' => 'md-6',
            ],
            'slug' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 255,
                'col' => 'md-6',
            ],
            'description' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
            ],
            'is_system' => [
                'type' => 'checkbox',
                'col' => 'md-6',
            ],
        ];
    }

    protected function configureDataTable($dt)
    {
        return $dt
            ->editColumn('is_system', fn ($row) => $row->is_system ? '<span class="badge bg-success">' . e(__('common.active')) . '</span>' : '<span class="badge bg-secondary">' . e(__('common.inactive')) . '</span>')
            
            ->addColumn('actions', fn ($row) => view(
                'admin._partials.datatable_actions',
                ['row' => $row, 'routeName' => $this->routeName()]
            )->render())
            ->rawColumns(['is_system', 'actions']);
    }

}