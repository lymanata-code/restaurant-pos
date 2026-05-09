<?php

namespace App\Http\Controllers\Admin\MenuItems;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return MenuItem::class;
    }

    protected function viewPath(): string
    {
        return 'admin.menu_items';
    }

    protected function routeName(): string
    {
        return 'admin.menu-items';
    }

    protected function translationNamespace(): string
    {
        return 'menu_items';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'item_code' => ['required','string','max:50'],
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'sale_price' => ['required','numeric','min:0'],
            'cost_price' => ['nullable','numeric','min:0'],
            'status' => ['required','in:active,sold_out,inactive'],
            'sort_order' => ['nullable','integer'],
            'is_combo' => ['nullable','boolean'],
            'track_inventory' => ['nullable','boolean'],
            'is_available' => ['nullable','boolean'],
        ];
    }

    protected function fields(): array
    {
        return [
            'item_code' => [
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
            'description' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
            ],
            'sale_price' => [
                'type' => 'number',
                'step' => 0.01,
                'required' => true,
                'col' => 'md-3',
                'default' => 0,
            ],
            'cost_price' => [
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
                    'sold_out' => 'Sold Out',
                    'inactive' => 'Inactive',
                ],
                'default' => 'active',
                'required' => true,
            ],
            'sort_order' => [
                'type' => 'number',
                'col' => 'md-3',
                'default' => 0,
            ],
            'is_combo' => [
                'type' => 'checkbox',
                'col' => 'md-3',
            ],
            'track_inventory' => [
                'type' => 'checkbox',
                'col' => 'md-3',
            ],
            'is_available' => [
                'type' => 'checkbox',
                'col' => 'md-3',
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