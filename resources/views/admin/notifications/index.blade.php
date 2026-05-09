@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('notifications.id') }}</th>
    <th>{{ __('notifications.title') }}</th>
    <th>{{ __('notifications.type') }}</th>
    <th>{{ __('notifications.is_read') }}</th>
    <th>{{ __('notifications.created_at') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'title', name: 'title', orderable: true },
    { data: 'type', name: 'type', width: '120px', orderable: true },
    { data: 'is_read', name: 'is_read', width: '110px', orderable: true },
    { data: 'created_at', name: 'created_at', width: '160px', orderable: true }
]
@endsection