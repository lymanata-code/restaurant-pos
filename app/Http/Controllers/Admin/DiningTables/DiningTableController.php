<?php

namespace App\Http\Controllers\Admin\DiningTables;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\DiningTable;
use Illuminate\Http\Request;

class DiningTableController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return DiningTable::class;
    }

    protected function viewPath(): string
    {
        return 'admin.dining_tables';
    }

    protected function routeName(): string
    {
        return 'admin.dining-tables';
    }

    protected function translationNamespace(): string
    {
        return 'dining_tables';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'table_code' => ['required','string','max:50'],
            'table_no' => ['required','string','max:50'],
            'capacity' => ['required','integer','min:1'],
            'status' => ['required','in:available,occupied,reserved,inactive'],
            'is_active' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'table_code' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 50,
                'col' => 'md-3',
            ],
            'table_no' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-3',
            ],
            'capacity' => [
                'type' => 'number',
                'col' => 'md-3',
                'default' => 1,
                'required' => true,
            ],
            'status' => [
                'type' => 'select',
                'col' => 'md-3',
                'options' => [
                    'available' => 'Available',
                    'occupied' => 'Occupied',
                    'reserved' => 'Reserved',
                    'inactive' => 'Inactive',
                ],
                'default' => 'available',
                'required' => true,
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
            
            ->addColumn('actions', fn ($row) => view(
                'admin._partials.datatable_actions',
                ['row' => $row, 'routeName' => $this->routeName()]
            )->render())
            ->rawColumns(['actions']);
    }

}