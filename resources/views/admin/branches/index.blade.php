@extends('admin._partials.crud_index')

@section('thead')
    <th>#</th>
    <th>{{ __('branches.code') }}</th>
    <th>{{ __('branches.name') }}</th>
    <th>{{ __('branches.phone') }}</th>
    <th>{{ __('branches.email') }}</th>
    <th>{{ __('branches.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id',         name: 'id', width: '60px' },
    { data: 'code',       name: 'code' },
    { data: 'name',       name: 'name' },
    { data: 'phone',      name: 'phone' },
    { data: 'email',      name: 'email' },
    { data: 'is_active',  name: 'is_active', width: '110px', searchable: false },
    { data: 'actions',    name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection
