@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('accounts.id') }}</th>
    <th>{{ __('accounts.account_code') }}</th>
    <th>{{ __('accounts.name') }}</th>
    <th>{{ __('accounts.account_type') }}</th>
    <th>{{ __('accounts.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'account_code', name: 'account_code',  },
    { data: 'name', name: 'name',  },
    { data: 'account_type', name: 'account_type',  },
    { data: 'is_active', name: 'is_active', width: '110px', , searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection