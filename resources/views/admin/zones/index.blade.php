@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('zones.id') }}</th>
    <th>{{ __('zones.name') }}</th>
    <th>{{ __('zones.sort_order') }}</th>
    <th>{{ __('zones.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'sort_order', name: 'sort_order', width: '110px',  },
    { data: 'is_active', name: 'is_active', width: '110px', , searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection