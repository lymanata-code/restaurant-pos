@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('inventory_categories.id') }}</th>
    <th>{{ __('inventory_categories.name') }}</th>
    <th>{{ __('inventory_categories.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'is_active', name: 'is_active', width: '110px', searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection