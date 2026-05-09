<?php

namespace App\Http\Controllers\Admin\Promotions;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Promotion::class;
    }

    protected function viewPath(): string
    {
        return 'admin.promotions';
    }

    protected function routeName(): string
    {
        return 'admin.promotions';
    }

    protected function translationNamespace(): string
    {
        return 'promotions';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'promotion_type' => ['required','in:bill_discount,item_discount,coupon,buy_x_get_y,happy_hour'],
            'discount_type' => ['required','in:percent,fixed,free_item'],
            'discount_value' => ['required','numeric','min:0'],
            'min_spend' => ['nullable','numeric','min:0'],
            'start_date' => ['nullable','date'],
            'end_date' => ['nullable','date','after_or_equal:start_date'],
            'is_active' => ['nullable','boolean'],
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
            'promotion_type' => [
                'type' => 'select',
                'required' => true,
                'col' => 'md-3',
                'options' => [
                    'bill_discount' => 'Bill Discount',
                    'item_discount' => 'Item Discount',
                    'coupon' => 'Coupon',
                    'buy_x_get_y' => 'Buy X Get Y',
                    'happy_hour' => 'Happy Hour',
                ],
            ],
            'discount_type' => [
                'type' => 'select',
                'required' => true,
                'col' => 'md-3',
                'options' => [
                    'percent' => 'Percent',
                    'fixed' => 'Fixed',
                    'free_item' => 'Free Item',
                ],
            ],
            'discount_value' => [
                'type' => 'number',
                'step' => 0.01,
                'col' => 'md-3',
                'default' => 0,
                'required' => true,
            ],
            'min_spend' => [
                'type' => 'number',
                'step' => 0.01,
                'col' => 'md-3',
                'default' => 0,
            ],
            'start_date' => [
                'type' => 'date',
                'col' => 'md-3',
            ],
            'end_date' => [
                'type' => 'date',
                'col' => 'md-3',
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