<?php

namespace App\Http\Controllers\Admin\MenuCategorys;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return MenuCategory::class;
    }

    protected function viewPath(): string
    {
        return 'admin.menu_categories';
    }

    protected function routeName(): string
    {
        return 'admin.menu-categories';
    }

    protected function translationNamespace(): string
    {
        return 'menu_categories';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'code' => ['nullable','string','max:50'],
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'sort_order' => ['nullable','integer'],
            'is_active' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'code' => [
                'type' => 'text',
                'col' => 'md-3',
                'maxlength' => 50,
            ],
            'name' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-9',
            ],
            'description' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
            ],
            'sort_order' => [
                'type' => 'number',
                'col' => 'md-6',
                'default' => 0,
            ],
            'is_active' => [
                'type' => 'checkbox',
                'col' => 'md-6',
                'default' => true,
            ],
        ];
    }

    protected function configureDataTable($dt)
    {
        return $dt
            ->editColumn('is_active', fn ($row) => $row->is_active ? '<span class="badge bg-success">' . e(__('common.active')) . '</span>' : '<span class="badge bg-secondary">' . e(__('common.inactive')) . '</span>')
            
            ->addColumn('actions', fn ($row) => view(
                'admin._partials.datatable_actions',
                ['row' => $row, 'routeName' => $this->routeName()]
            )->render())
            ->rawColumns(['is_active', 'actions']);
    }

}