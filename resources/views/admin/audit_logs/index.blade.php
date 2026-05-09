@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('audit_logs.id') }}</th>
    <th>{{ __('audit_logs.user_id') }}</th>
    <th>{{ __('audit_logs.action') }}</th>
    <th>{{ __('audit_logs.model_type') }}</th>
    <th>{{ __('audit_logs.model_id') }}</th>
    <th>{{ __('audit_logs.created_at') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'user_id', name: 'user_id', width: '110px', orderable: true },
    { data: 'action', name: 'action', width: '120px', orderable: true },
    { data: 'model_type', name: 'model_type', orderable: true },
    { data: 'model_id', name: 'model_id', width: '110px', orderable: true },
    { data: 'created_at', name: 'created_at', width: '160px', orderable: true }
]
@endsection