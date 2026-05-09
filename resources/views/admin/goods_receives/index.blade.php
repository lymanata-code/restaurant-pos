@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('goods_receives.id') }}</th>
    <th>{{ __('goods_receives.gr_no') }}</th>
    <th>{{ __('goods_receives.supplier_id') }}</th>
    <th>{{ __('goods_receives.receive_date') }}</th>
    <th>{{ __('goods_receives.status') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'gr_no', name: 'gr_no', orderable: true },
    { data: 'supplier_id', name: 'supplier_id', width: '110px', orderable: true },
    { data: 'receive_date', name: 'receive_date', width: '120px', orderable: true },
    { data: 'status', name: 'status', width: '110px', orderable: true }
]
@endsection