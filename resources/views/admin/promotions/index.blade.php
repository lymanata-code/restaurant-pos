@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('promotions.id') }}</th>
    <th>{{ __('promotions.name') }}</th>
    <th>{{ __('promotions.promotion_type') }}</th>
    <th>{{ __('promotions.discount_type') }}</th>
    <th>{{ __('promotions.is_active') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'name', name: 'name',  },
    { data: 'promotion_type', name: 'promotion_type',  },
    { data: 'discount_type', name: 'discount_type',  },
    { data: 'is_active', name: 'is_active', width: '110px', searchable: false },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection