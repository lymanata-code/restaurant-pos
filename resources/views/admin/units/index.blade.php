@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('units.id') }}</th>
    <th>{{ __('units.name') }}</th>
    <th>{{ __('units.symbol') }}</th>
    <th>{{ __('units.unit_type') }}</th>
    <th>{{ __('units.is_base') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'symbol', name: 'symbol',  },
    { data: 'unit_type', name: 'unit_type',  },
    { data: 'is_base', name: 'is_base', width: '110px', searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection