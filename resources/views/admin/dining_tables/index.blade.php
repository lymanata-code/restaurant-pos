@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('dining_tables.id') }}</th>
    <th>{{ __('dining_tables.table_code') }}</th>
    <th>{{ __('dining_tables.table_no') }}</th>
    <th>{{ __('dining_tables.capacity') }}</th>
    <th>{{ __('dining_tables.status') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'table_code', name: 'table_code',  },
    { data: 'table_no', name: 'table_no',  },
    { data: 'capacity', name: 'capacity', width: '110px',  },
    { data: 'status', name: 'status', width: '110px',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection