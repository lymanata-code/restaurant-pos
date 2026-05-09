@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('tax_rates.id') }}</th>
    <th>{{ __('tax_rates.name') }}</th>
    <th>{{ __('tax_rates.rate') }}</th>
    <th>{{ __('tax_rates.type') }}</th>
    <th>{{ __('tax_rates.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'rate', name: 'rate',  },
    { data: 'type', name: 'type',  },
    { data: 'is_active', name: 'is_active', width: '110px', , searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection