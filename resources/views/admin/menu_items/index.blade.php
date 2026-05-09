@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('menu_items.id') }}</th>
    <th>{{ __('menu_items.item_code') }}</th>
    <th>{{ __('menu_items.name') }}</th>
    <th>{{ __('menu_items.sale_price') }}</th>
    <th>{{ __('menu_items.status') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'item_code', name: 'item_code',  },
    { data: 'name', name: 'name',  },
    { data: 'sale_price', name: 'sale_price', width: '110px',  },
    { data: 'status', name: 'status', width: '110px',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection