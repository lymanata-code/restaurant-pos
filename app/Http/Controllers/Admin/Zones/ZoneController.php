<?php

namespace App\Http\Controllers\Admin\Zones;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Zone::class;
    }

    protected function viewPath(): string
    {
        return 'admin.zones';
    }

    protected function routeName(): string
    {
        return 'admin.zones';
    }

    protected function translationNamespace(): string
    {
        return 'zones';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'sort_order' => ['nullable','integer'],
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