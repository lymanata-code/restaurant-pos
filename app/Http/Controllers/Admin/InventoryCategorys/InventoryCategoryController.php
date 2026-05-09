<?php

namespace App\Http\Controllers\Admin\InventoryCategorys;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;

class InventoryCategoryController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return InventoryCategory::class;
    }

    protected function viewPath(): string
    {
        return 'admin.inventory_categories';
    }

    protected function routeName(): string
    {
        return 'admin.inventory-categories';
    }

    protected function translationNamespace(): string
    {
        return 'inventory_categories';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'name' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-6',
            ],
            'description' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
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