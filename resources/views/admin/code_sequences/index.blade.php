@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('code_sequences.id') }}</th>
    <th>{{ __('code_sequences.name') }}</th>
    <th>{{ __('code_sequences.prefix') }}</th>
    <th>{{ __('code_sequences.next_number') }}</th>
    <th>{{ __('code_sequences.padding') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'name', name: 'name', orderable: true },
    { data: 'prefix', name: 'prefix', width: '110px', orderable: true },
    { data: 'next_number', name: 'next_number', width: '120px', orderable: true },
    { data: 'padding', name: 'padding', width: '100px', orderable: true }
]
@endsection