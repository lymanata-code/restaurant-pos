<?php

namespace App\Http\Controllers\Admin\PaymentMethods;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return PaymentMethod::class;
    }

    protected function viewPath(): string
    {
        return 'admin.payment_methods';
    }

    protected function routeName(): string
    {
        return 'admin.payment-methods';
    }

    protected function translationNamespace(): string
    {
        return 'payment_methods';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'code' => ['required','string','max:50'],
            'name' => ['required','string','max:255'],
            'type' => ['required','in:cash,aba,khqr,wing,bank_transfer,card,other'],
            'requires_reference' => ['nullable','boolean'],
            'is_online' => ['nullable','boolean'],
            'is_active' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'code' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 50,
                'col' => 'md-3',
            ],
            'name' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 255,
                'col' => 'md-6',
            ],
            'type' => [
                'type' => 'select',
                'required' => true,
                'col' => 'md-3',
                'options' => [
                    'cash' => 'Cash',
                    'aba' => 'ABA',
                    'khqr' => 'KHQR',
                    'wing' => 'Wing',
                    'bank_transfer' => 'Bank Transfer',
                    'card' => 'Card',
                    'other' => 'Other',
                ],
            ],
            'requires_reference' => [
                'type' => 'checkbox',
                'col' => 'md-4',
            ],
            'is_online' => [
                'type' => 'checkbox',
                'col' => 'md-4',
            ],
            'is_active' => [
                'type' => 'checkbox',
                'col' => 'md-4',
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