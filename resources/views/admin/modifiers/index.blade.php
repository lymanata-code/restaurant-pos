@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('modifiers.id') }}</th>
    <th>{{ __('modifiers.name') }}</th>
    <th>{{ __('modifiers.price_delta') }}</th>
    <th>{{ __('modifiers.is_default') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'name', name: 'name', orderable: true },
    { data: 'price_delta', name: 'price_delta', width: '110px', orderable: true },
    { data: 'is_default', name: 'is_default', width: '110px', orderable: true }
]
@endsection