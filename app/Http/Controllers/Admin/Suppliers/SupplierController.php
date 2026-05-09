<?php

namespace App\Http\Controllers\Admin\Suppliers;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Supplier::class;
    }

    protected function viewPath(): string
    {
        return 'admin.suppliers';
    }

    protected function routeName(): string
    {
        return 'admin.suppliers';
    }

    protected function translationNamespace(): string
    {
        return 'suppliers';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'supplier_code' => ['required','string','max:80'],
            'name' => ['required','string','max:255'],
            'contact_person' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'address' => ['nullable','string'],
            'status' => ['required','in:active,inactive,blocked'],
        ];
    }

    protected function fields(): array
    {
        return [
            'supplier_code' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 80,
                'col' => 'md-3',
            ],
            'name' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-9',
            ],
            'contact_person' => [
                'type' => 'text',
                'col' => 'md-4',
            ],
            'phone' => [
                'type' => 'text',
                'col' => 'md-4',
            ],
            'email' => [
                'type' => 'email',
                'col' => 'md-4',
            ],
            'address' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
            ],
            'status' => [
                'type' => 'select',
                'col' => 'md-6',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'blocked' => 'Blocked',
                ],
                'default' => 'active',
                'required' => true,
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