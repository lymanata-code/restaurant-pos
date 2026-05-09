@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('printers.id') }}</th>
    <th>{{ __('printers.name') }}</th>
    <th>{{ __('printers.printer_type') }}</th>
    <th>{{ __('printers.ip_address') }}</th>
    <th>{{ __('printers.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'printer_type', name: 'printer_type',  },
    { data: 'ip_address', name: 'ip_address',  },
    { data: 'is_active', name: 'is_active', width: '110px', , searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection