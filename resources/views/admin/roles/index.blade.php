@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('roles.id') }}</th>
    <th>{{ __('roles.name') }}</th>
    <th>{{ __('roles.slug') }}</th>
    <th>{{ __('roles.is_system') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'slug', name: 'slug',  },
    { data: 'is_system', name: 'is_system', width: '110px', , searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection