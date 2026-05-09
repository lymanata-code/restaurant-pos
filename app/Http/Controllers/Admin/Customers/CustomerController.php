<?php

namespace App\Http\Controllers\Admin\Customers;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Customer::class;
    }

    protected function viewPath(): string
    {
        return 'admin.customers';
    }

    protected function routeName(): string
    {
        return 'admin.customers';
    }

    protected function translationNamespace(): string
    {
        return 'customers';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'customer_code' => ['required','string','max:50'],
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'membership_no' => ['nullable','string','max:80'],
            'address' => ['nullable','string'],
            'points' => ['nullable','integer','min:0'],
            'credit_limit' => ['nullable','numeric','min:0'],
            'status' => ['required','in:active,inactive,blocked'],
            'is_blacklisted' => ['nullable','boolean'],
            'notes' => ['nullable','string'],
        ];
    }

    protected function fields(): array
    {
        return [
            'customer_code' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 50,
                'col' => 'md-3',
            ],
            'name' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-9',
            ],
            'phone' => [
                'type' => 'text',
                'col' => 'md-4',
            ],
            'email' => [
                'type' => 'email',
                'col' => 'md-4',
            ],
            'membership_no' => [
                'type' => 'text',
                'col' => 'md-4',
            ],
            'address' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
            ],
            'points' => [
                'type' => 'number',
                'col' => 'md-3',
                'default' => 0,
            ],
            'credit_limit' => [
                'type' => 'number',
                'step' => 0.01,
                'col' => 'md-3',
                'default' => 0,
            ],
            'status' => [
                'type' => 'select',
                'col' => 'md-3',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'blocked' => 'Blocked',
                ],
                'default' => 'active',
                'required' => true,
            ],
            'is_blacklisted' => [
                'type' => 'checkbox',
                'col' => 'md-3',
            ],
            'notes' => [
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