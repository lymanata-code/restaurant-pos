@extends('admin._partials.crud_index')

@section('thead')
    <th>{{ __('expenses.id') }}</th>
    <th>{{ __('expenses.expense_no') }}</th>
    <th>{{ __('expenses.title') }}</th>
    <th>{{ __('expenses.amount') }}</th>
    <th>{{ __('expenses.expense_date') }}</th>
    <th class="text-end">{{ __('common.actions') }}</th>
@endsection

@section('columns_json')
[
    { data: 'id', name: 'id', width: '60px',  },
    { data: 'expense_no', name: 'expense_no',  },
    { data: 'title', name: 'title',  },
    { data: 'amount', name: 'amount', width: '120px',  },
    { data: 'expense_date', name: 'expense_date', width: '120px',  },

    { data: 'actions', name: 'actions', width: '110px', searchable: false, orderable: false, className: 'text-end' }
]
@endsection