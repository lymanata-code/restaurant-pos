@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('journal_entries.id') }}</th>
    <th>{{ __('journal_entries.entry_no') }}</th>
    <th>{{ __('journal_entries.entry_date') }}</th>
    <th>{{ __('journal_entries.description') }}</th>
    <th>{{ __('journal_entries.total_debit') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px', orderable: true },
    { data: 'entry_no', name: 'entry_no', orderable: true },
    { data: 'entry_date', name: 'entry_date', width: '120px', orderable: true },
    { data: 'description', name: 'description', orderable: true },
    { data: 'total_debit', name: 'total_debit', width: '120px', orderable: true }
]
@endsection