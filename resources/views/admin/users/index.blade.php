@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('users.id') }}</th>
    <th>{{ __('users.name') }}</th>
    <th>{{ __('users.username') }}</th>
    <th>{{ __('users.email') }}</th>
    <th>{{ __('users.status') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'username', name: 'username',  },
    { data: 'email', name: 'email',  },
    { data: 'status', name: 'status', width: '110px',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection