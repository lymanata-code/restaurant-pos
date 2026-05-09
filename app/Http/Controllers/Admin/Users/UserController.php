<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Admin\AbstractCrudController;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends AbstractCrudController
{
    protected function modelClass(): string
    {
        return User::class;
    }

    protected function viewPath(): string
    {
        return 'admin.users';
    }

    protected function routeName(): string
    {
        return 'admin.users';
    }

    protected function translationNamespace(): string
    {
        return 'users';
    }

    protected function rules(?int $id = null): array
    {
        $isCreate = $id === null;
        $isCreateMode = $isCreate;
        return [
            'name' => ['required','string','max:255'],
            'username' => ['required','string','max:100'],
            'email' => ['nullable','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'password' => [$isCreateMode ? 'required' : 'nullable','string','min:6','max:255'],
            'status' => ['required','in:active,inactive,locked'],
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
            'username' => [
                'type' => 'text',
                'required' => true,
                'col' => 'md-6',
            ],
            'email' => [
                'type' => 'email',
                'col' => 'md-6',
            ],
            'phone' => [
                'type' => 'text',
                'col' => 'md-6',
            ],
            'password' => [
                'type' => 'password',
                'col' => 'md-6',
            ],
            'status' => [
                'type' => 'select',
                'col' => 'md-6',
                'options' => [
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'locked' => 'Locked',
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


    protected function mapPayload(Request $request): array
    {
        $data = parent::mapPayload($request);
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        return $data;
    }
}