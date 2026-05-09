@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('coupons.id') }}</th>
    <th>{{ __('coupons.code') }}</th>
    <th>{{ __('coupons.promotion_id') }}</th>
    <th>{{ __('coupons.is_used') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'code', name: 'code', orderable: true },
    { data: 'promotion_id', name: 'promotion_id', width: '110px', orderable: true },
    { data: 'is_used', name: 'is_used', width: '110px', orderable: true }
]
@endsection