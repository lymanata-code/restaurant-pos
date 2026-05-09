@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('stock_items.id') }}</th>
    <th>{{ __('stock_items.item_code') }}</th>
    <th>{{ __('stock_items.name') }}</th>
    <th>{{ __('stock_items.quantity_on_hand') }}</th>
    <th>{{ __('stock_items.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'item_code', name: 'item_code',  },
    { data: 'name', name: 'name',  },
    { data: 'quantity_on_hand', name: 'quantity_on_hand', width: '110px',  },
    { data: 'is_active', name: 'is_active', width: '110px', searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection