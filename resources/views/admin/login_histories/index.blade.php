@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('login_histories.id') }}</th>
    <th>{{ __('login_histories.username') }}</th>
    <th>{{ __('login_histories.ip_address') }}</th>
    <th>{{ __('login_histories.success') }}</th>
    <th>{{ __('login_histories.logged_in_at') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'username', name: 'username', orderable: true },
    { data: 'ip_address', name: 'ip_address', orderable: true },
    { data: 'success', name: 'success', width: '100px', orderable: true },
    { data: 'logged_in_at', name: 'logged_in_at', width: '160px', orderable: true }
]
@endsection