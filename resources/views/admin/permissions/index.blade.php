@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('permissions.id') }}</th>
    <th>{{ __('permissions.module') }}</th>
    <th>{{ __('permissions.name') }}</th>
    <th>{{ __('permissions.slug') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'module', name: 'module',  },
    { data: 'name', name: 'name',  },
    { data: 'slug', name: 'slug',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection