<?php

namespace App\Http\Controllers\Admin\Printers;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Printer;
use Illuminate\Http\Request;

class PrinterController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Printer::class;
    }

    protected function viewPath(): string
    {
        return 'admin.printers';
    }

    protected function routeName(): string
    {
        return 'admin.printers';
    }

    protected function translationNamespace(): string
    {
        return 'printers';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'name' => ['required','string','max:255'],
            'printer_type' => ['required','in:network,usb,bluetooth'],
            'paper_size' => ['nullable','string','max:50'],
            'ip_address' => ['nullable','string','max:100'],
            'port' => ['nullable','integer'],
            'is_default' => ['nullable','boolean'],
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
            'printer_type' => [
                'type' => 'select',
                'required' => true,
                'col' => 'md-3',
                'options' => [
                    'network' => 'Network',
                    'usb' => 'USB',
                    'bluetooth' => 'Bluetooth',
                ],
            ],
            'paper_size' => [
                'type' => 'select',
                'col' => 'md-3',
                'options' => [
                    '80mm' => '80mm',
                    '58mm' => '58mm',
                    'A4' => 'A4',
                ],
            ],
            'ip_address' => [
                'type' => 'text',
                'maxlength' => 100,
                'col' => 'md-6',
            ],
            'port' => [
                'type' => 'number',
                'col' => 'md-6',
            ],
            'is_default' => [
                'type' => 'checkbox',
                'col' => 'md-6',
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