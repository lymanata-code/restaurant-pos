@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('stock_adjustments.id') }}</th>
    <th>{{ __('stock_adjustments.adjustment_no') }}</th>
    <th>{{ __('stock_adjustments.reason') }}</th>
    <th>{{ __('stock_adjustments.adjustment_date') }}</th>
    <th>{{ __('stock_adjustments.status') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'adjustment_no', name: 'adjustment_no', orderable: true },
    { data: 'reason', name: 'reason', width: '120px', orderable: true },
    { data: 'adjustment_date', name: 'adjustment_date', width: '120px', orderable: true },
    { data: 'status', name: 'status', width: '110px', orderable: true }
]
@endsection