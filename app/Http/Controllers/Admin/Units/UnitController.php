<?php

namespace App\Http\Controllers\Admin\Units;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Unit::class;
    }

    protected function viewPath(): string
    {
        return 'admin.units';
    }

    protected function routeName(): string
    {
        return 'admin.units';
    }

    protected function translationNamespace(): string
    {
        return 'units';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'symbol' => ['required','string','max:20'],
            'unit_type' => ['nullable','in:weight,volume,piece'],
            'is_base' => ['nullable','boolean'],
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
            'symbol' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 20,
                'col' => 'md-3',
            ],
            'unit_type' => [
                'type' => 'select',
                'col' => 'md-3',
                'options' => [
                    'weight' => 'Weight',
                    'volume' => 'Volume',
                    'piece' => 'Piece',
                ],
            ],
            'is_base' => [
                'type' => 'checkbox',
                'col' => 'md-6',
            ],
        ];
    }

    protected function configureDataTable($dt)
    {
        return $dt
            ->editColumn('is_base', fn ($row) => $row->is_base ? '<span class="badge bg-success">' . e(__('common.active')) . '</span>' : '<span class="badge bg-secondary">' . e(__('common.inactive')) . '</span>')
            
            ->addColumn('actions', fn ($row) => view(
                'admin._partials.datatable_actions',
                ['row' => $row, 'routeName' => $this->routeName()]
            )->render())
            ->rawColumns(['is_base', 'actions']);
    }

}