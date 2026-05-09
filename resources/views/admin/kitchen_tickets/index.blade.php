@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('kitchen_tickets.id') }}</th>
    <th>{{ __('kitchen_tickets.ticket_no') }}</th>
    <th>{{ __('kitchen_tickets.status') }}</th>
    <th>{{ __('kitchen_tickets.issued_at') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'ticket_no', name: 'ticket_no', orderable: true },
    { data: 'status', name: 'status', width: '110px', orderable: true },
    { data: 'issued_at', name: 'issued_at', width: '160px', orderable: true }
]
@endsection