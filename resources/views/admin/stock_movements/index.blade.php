@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('stock_movements.id') }}</th>
    <th>{{ __('stock_movements.stock_item_id') }}</th>
    <th>{{ __('stock_movements.movement_type') }}</th>
    <th>{{ __('stock_movements.quantity') }}</th>
    <th>{{ __('stock_movements.created_at') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'stock_item_id', name: 'stock_item_id', width: '110px', orderable: true },
    { data: 'movement_type', name: 'movement_type', width: '120px', orderable: true },
    { data: 'quantity', name: 'quantity', width: '110px', orderable: true },
    { data: 'created_at', name: 'created_at', width: '160px', orderable: true }
]
@endsection