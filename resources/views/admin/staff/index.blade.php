@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('staff.id') }}</th>
    <th>{{ __('staff.staff_code') }}</th>
    <th>{{ __('staff.name') }}</th>
    <th>{{ __('staff.phone') }}</th>
    <th>{{ __('staff.position') }}</th>
    <th>{{ __('staff.status') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'staff_code', name: 'staff_code',  },
    { data: 'name', name: 'name',  },
    { data: 'phone', name: 'phone',  },
    { data: 'position', name: 'position',  },
    { data: 'status', name: 'status',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection