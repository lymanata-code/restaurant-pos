<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Account::class;
    }

    protected function viewPath(): string
    {
        return 'admin.accounts';
    }

    protected function routeName(): string
    {
        return 'admin.accounts';
    }

    protected function translationNamespace(): string
    {
        return 'accounts';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'account_code' => ['required','string','max:50'],
            'name' => ['required','string','max:255'],
            'account_type' => ['required','in:asset,liability,equity,revenue,expense,cost_of_goods_sold'],
            'is_active' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'account_code' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 50,
                'col' => 'md-4',
            ],
            'name' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-8',
            ],
            'account_type' => [
                'type' => 'select',
                'col' => 'md-6',
                'required' => true,
                'options' => [
                    'asset' => 'Asset',
                    'liability' => 'Liability',
                    'equity' => 'Equity',
                    'revenue' => 'Revenue',
                    'expense' => 'Expense',
                    'cost_of_goods_sold' => 'COGS',
                ],
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