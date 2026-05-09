@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('customers.id') }}</th>
    <th>{{ __('customers.customer_code') }}</th>
    <th>{{ __('customers.name') }}</th>
    <th>{{ __('customers.phone') }}</th>
    <th>{{ __('customers.email') }}</th>
    <th>{{ __('customers.status') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'customer_code', name: 'customer_code',  },
    { data: 'name', name: 'name',  },
    { data: 'phone', name: 'phone',  },
    { data: 'email', name: 'email',  },
    { data: 'status', name: 'status', width: '110px',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection