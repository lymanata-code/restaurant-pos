@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('purchase_orders.id') }}</th>
    <th>{{ __('purchase_orders.po_no') }}</th>
    <th>{{ __('purchase_orders.supplier_id') }}</th>
    <th>{{ __('purchase_orders.order_date') }}</th>
    <th>{{ __('purchase_orders.status') }}</th>
    <th>{{ __('purchase_orders.total_amount') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'po_no', name: 'po_no', orderable: true },
    { data: 'supplier_id', name: 'supplier_id', width: '110px', orderable: true },
    { data: 'order_date', name: 'order_date', width: '120px', orderable: true },
    { data: 'status', name: 'status', width: '110px', orderable: true },
    { data: 'total_amount', name: 'total_amount', width: '120px', orderable: true }
]
@endsection