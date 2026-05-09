@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('kitchen_stations.id') }}</th>
    <th>{{ __('kitchen_stations.name') }}</th>
    <th>{{ __('kitchen_stations.printer_id') }}</th>
    <th>{{ __('kitchen_stations.is_active') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'name', name: 'name', orderable: true },
    { data: 'printer_id', name: 'printer_id', width: '110px', orderable: true },
    { data: 'is_active', name: 'is_active', width: '110px', orderable: true }
]
@endsection