@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('modifier_groups.id') }}</th>
    <th>{{ __('modifier_groups.name') }}</th>
    <th>{{ __('modifier_groups.min_select') }}</th>
    <th>{{ __('modifier_groups.max_select') }}</th>
    <th>{{ __('modifier_groups.is_required') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'name', name: 'name', orderable: true },
    { data: 'min_select', name: 'min_select', width: '110px', orderable: true },
    { data: 'max_select', name: 'max_select', width: '110px', orderable: true },
    { data: 'is_required', name: 'is_required', width: '110px', orderable: true }
]
@endsection