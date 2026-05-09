<?php

namespace App\Http\Controllers\Admin\StockItems;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return StockItem::class;
    }

    protected function viewPath(): string
    {
        return 'admin.stock_items';
    }

    protected function routeName(): string
    {
        return 'admin.stock-items';
    }

    protected function translationNamespace(): string
    {
        return 'stock_items';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'item_code' => ['required','string','max:80'],
            'name' => ['required','string','max:255'],
            'purchase_price' => ['nullable','numeric','min:0'],
            'quantity_on_hand' => ['nullable','numeric'],
            'reorder_level' => ['nullable','numeric','min:0'],
            'reorder_quantity' => ['nullable','numeric','min:0'],
            'expiry_date' => ['nullable','date'],
            'is_ingredient' => ['nullable','boolean'],
            'is_active' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'item_code' => [
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
            'purchase_price' => [
                'type' => 'number',
                'step' => 0.01,
                'col' => 'md-3',
                'default' => 0,
            ],
            'quantity_on_hand' => [
                'type' => 'number',
                'step' => 0.0001,
                'col' => 'md-3',
                'default' => 0,
            ],
            'reorder_level' => [
                'type' => 'number',
                'step' => 0.0001,
                'col' => 'md-3',
                'default' => 0,
            ],
            'reorder_quantity' => [
                'type' => 'number',
                'step' => 0.0001,
                'col' => 'md-3',
                'default' => 0,
            ],
            'expiry_date' => [
                'type' => 'date',
                'col' => 'md-6',
            ],
            'is_ingredient' => [
                'type' => 'checkbox',
                'col' => 'md-3',
                'default' => true,
            ],
            'is_active' => [
                'type' => 'checkbox',
                'col' => 'md-3',
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