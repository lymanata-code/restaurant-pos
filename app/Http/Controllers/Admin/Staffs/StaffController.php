<?php

namespace App\Http\Controllers\Admin\Staffs;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Staff::class;
    }

    protected function viewPath(): string
    {
        return 'admin.staff';
    }

    protected function routeName(): string
    {
        return 'admin.staff';
    }

    protected function translationNamespace(): string
    {
        return 'staff';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'staff_code' => ['required','string','max:50'],
            'name' => ['required','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'position' => ['nullable','string','max:255'],
            'address' => ['nullable','string'],
            'hire_date' => ['nullable','date'],
            'status' => ['required','in:active,inactive,terminated'],
        ];
    }

    protected function fields(): array
    {
        return [
            'staff_code' => [
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
            'phone' => [
                'type' => 'text',
                'col' => 'md-4',
            ],
            'email' => [
                'type' => 'email',
                'col' => 'md-4',
            ],
            'position' => [
                'type' => 'text',
                'col' => 'md-4',
            ],
            'address' => [
                'type' => 'textarea',
                'col' => 'md-12',
                'rows' => 2,
            ],
            'hire_date' => [
                'type' => 'date',
                'col' => 'md-4',
            ],
            'status' => [
                'type' => 'select',
                'col' => 'md-4',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'terminated' => 'Terminated',
                ],
                'required' => true,
                'default' => 'active',
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