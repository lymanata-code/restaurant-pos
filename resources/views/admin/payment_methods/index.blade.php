@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('payment_methods.id') }}</th>
    <th>{{ __('payment_methods.code') }}</th>
    <th>{{ __('payment_methods.name') }}</th>
    <th>{{ __('payment_methods.type') }}</th>
    <th>{{ __('payment_methods.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'code', name: 'code',  },
    { data: 'name', name: 'name',  },
    { data: 'type', name: 'type',  },
    { data: 'is_active', name: 'is_active', width: '110px', , searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection