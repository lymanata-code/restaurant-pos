<?php

namespace App\Http\Controllers\Admin\Branches;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class BranchController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return Restaurant::class;
    }

    protected function viewPath(): string
    {
        return 'admin.branches';
    }

    protected function routeName(): string
    {
        return 'admin.branches';
    }

    protected function translationNamespace(): string
    {
        return 'branches';
    }

    protected function rules(?int $id = null): array
    {
        return [
            'code'           => ['required', 'string', 'max:50',
                \Illuminate\Validation\Rule::unique('restaurants', 'code')->ignore($id)],
            'name'           => ['required', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'email'          => ['nullable', 'email', 'max:255'],
            'address'        => ['nullable', 'string'],
            'tax_number'     => ['nullable', 'string', 'max:100'],
            'receipt_header' => ['nullable', 'string'],
            'receipt_footer' => ['nullable', 'string'],
            'refund_policy'  => ['nullable', 'string'],
            'is_active'      => ['nullable', 'boolean'],
        ];
    }

    protected function mapPayload(Request $request): array
    {
        $data = parent::mapPayload($request);
        $data['is_active'] = (bool) $request->boolean('is_active', true);
        return $data;
    }

    protected function configureDataTable($dt)
    {
        return $dt
            ->editColumn('is_active', fn ($row) => $row->is_active
                ? '<span class="badge bg-success">' . e(__('common.active')) . '</span>'
                : '<span class="badge bg-secondary">' . e(__('common.inactive')) . '</span>')
            ->addColumn('actions', fn ($row) => view(
                'admin._partials.datatable_actions',
                ['row' => $row, 'routeName' => $this->routeName()]
            )->render())
            ->rawColumns(['is_active', 'actions']);
    }
}
