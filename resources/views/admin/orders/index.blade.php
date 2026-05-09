@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('orders.id') }}</th>
    <th>{{ __('orders.order_no') }}</th>
    <th>{{ __('orders.order_type') }}</th>
    <th>{{ __('orders.status') }}</th>
    <th>{{ __('orders.total_amount') }}</th>
    <th>{{ __('orders.created_at') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'order_no', name: 'order_no', orderable: true },
    { data: 'order_type', name: 'order_type', orderable: true },
    { data: 'status', name: 'status', width: '110px', orderable: true },
    { data: 'total_amount', name: 'total_amount', width: '120px', orderable: true },
    { data: 'created_at', name: 'created_at', width: '160px', orderable: true }
]
@endsection