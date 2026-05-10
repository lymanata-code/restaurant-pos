@extends('admin.layouts.admin_layout')

@section('pageTitle', __('menu.pos_register'))
@section('breadcrumb_title', __('menu.pos'))
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('menu.pos') }}</li>
    <li class="breadcrumb-item active">{{ __('menu.pos_register') }}</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="card-title mb-0">{{ __('pos.open_tabs') }}</h5>
                <a href="{{ route('admin.pos.terminal') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i>{{ __('menu.pos_terminal') }}
                </a>
            </div>

            @if($openOrders->isEmpty())
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-1"></i>{{ __('pos.no_open_tabs') }}
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('orders.order_no') }}</th>
                                <th>{{ __('menu.dining_tables') }}</th>
                                <th class="text-end">{{ __('orders.total_amount') }}</th>
                                <th class="text-center">{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($openOrders as $o)
                                <tr>
                                    <td>{{ $o->id }}</td>
                                    <td>{{ $o->order_no ?? '#'.$o->id }}</td>
                                    <td>{{ $o->table->table_no ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($o->total_amount ?? 0, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>{{ __('common.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
