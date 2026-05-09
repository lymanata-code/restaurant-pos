@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('suppliers.id') }}</th>
    <th>{{ __('suppliers.supplier_code') }}</th>
    <th>{{ __('suppliers.name') }}</th>
    <th>{{ __('suppliers.phone') }}</th>
    <th>{{ __('suppliers.email') }}</th>
    <th>{{ __('suppliers.status') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'supplier_code', name: 'supplier_code',  },
    { data: 'name', name: 'name',  },
    { data: 'phone', name: 'phone',  },
    { data: 'email', name: 'email',  },
    { data: 'status', name: 'status', width: '110px',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection