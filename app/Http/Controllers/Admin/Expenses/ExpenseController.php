<?php

namespace App\Http\Controllers\Admin\Expenses;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Expense::class;
    }

    protected function viewPath(): string
    {
        return 'admin.expenses';
    }

    protected function routeName(): string
    {
        return 'admin.expenses';
    }

    protected function translationNamespace(): string
    {
        return 'expenses';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'expense_no' => ['required','string','max:80'],
            'title' => ['required','string','max:255'],
            'amount' => ['required','numeric','min:0'],
            'expense_date' => ['nullable','date'],
            'reference_no' => ['nullable','string','max:255'],
            'description' => ['nullable','string'],
        ];
    }

    protected function fields(): array
    {
        return [
            'expense_no' => [
                'type' => 'text',
                'required' => true,
                'maxlength' => 80,
                'col' => 'md-4',
            ],
            'title' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-8',
            ],
            'amount' => [
                'type' => 'number',
                'step' => 0.01,
                'required' => true,
                'col' => 'md-3',
            ],
            'expense_date' => [
                'type' => 'date',
                'col' => 'md-3',
            ],
            'reference_no' => [
                'type' => 'text',
                'col' => 'md-3',
            ],
            'description' => [
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