<?php

namespace App\Http\Controllers\Admin\TaxRates;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return TaxRate::class;
    }

    protected function viewPath(): string
    {
        return 'admin.tax_rates';
    }

    protected function routeName(): string
    {
        return 'admin.tax-rates';
    }

    protected function translationNamespace(): string
    {
        return 'tax_rates';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'rate' => ['required','numeric'],
            'type' => ['required','in:tax,service_charge'],
            'is_inclusive' => ['nullable','boolean'],
            'is_active' => ['nullable','boolean'],
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
            'rate' => [
                'type' => 'number',
                'step' => 0.0001,
                'required' => true,
                'col' => 'md-3',
            ],
            'type' => [
                'type' => 'select',
                'required' => true,
                'col' => 'md-3',
                'options' => [
                    'tax' => 'Tax',
                    'service_charge' => 'Service Charge',
                ],
            ],
            'is_inclusive' => [
                'type' => 'checkbox',
                'col' => 'md-6',
                'default' => false,
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