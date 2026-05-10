@extends('admin.layouts.admin_layout')

@section('pageTitle', __('menu.pos_terminal'))
@section('breadcrumb_title', __('menu.pos'))
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('menu.pos') }}</li>
    <li class="breadcrumb-item active">{{ __('menu.pos_terminal') }}</li>
@endsection

@section('content')
    <div class="row g-3">
        @php
            $kpis = [
                ['title' => __('menu.orders').' ('.__('common.today').')', 'value' => $stats['orders_today'], 'icon' => 'bi-receipt',     'color' => 'primary'],
                ['title' => __('pos.open_tabs'),                           'value' => $stats['open_tabs'],    'icon' => 'bi-hourglass-split', 'color' => 'warning'],
                ['title' => __('menu.dining_tables'),                      'value' => $stats['tables_total'], 'icon' => 'bi-grid-3x3-gap', 'color' => 'success'],
            ];
        @endphp
        @foreach($kpis as $c)
            <div class="col-12 col-md-4">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-muted small">{{ $c['title'] }}</div>
                            <div class="fs-3 fw-bold">{{ number_format($c['value']) }}</div>
                        </div>
                        <div class="rounded-circle bg-{{ $c['color'] }} bg-opacity-10 text-{{ $c['color'] }}
                                    d-flex align-items-center justify-content-center"
                             style="width: 52px; height: 52px;">
                            <i class="bi {{ $c['icon'] }} fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mt-1">
        <div class="col-12 col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h5 class="card-title mb-0">{{ __('pos.tables_grid') }}</h5>
                        <a href="{{ route('admin.dining-tables.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-grid-3x3-gap me-1"></i>{{ __('common.manage') }}
                        </a>
                    </div>

                    @if($tables->isEmpty())
                        <div class="text-muted">{{ __('pos.no_tables_yet') }}</div>
                    @else
                        <div class="row g-2">
                            @foreach($tables as $table)
                                <div class="col-6 col-sm-4 col-md-3">
                                    <a href="{{ route('admin.orders.index') }}?table={{ $table->id }}"
                                       class="card text-decoration-none border h-100">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="fw-semibold">{{ $table->table_no }}</div>
                                                <span class="badge bg-light text-dark">{{ $table->capacity ?? '-' }} <i class="bi bi-person"></i></span>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                {{ $table->zone->name ?? __('common.unzoned') }}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ __('pos.quick_actions') }}</h5>
                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                            <i class="bi bi-receipt me-1"></i> {{ __('pos.new_counter_sale') }}
                        </a>
                        <a href="{{ route('admin.pos.register') }}" class="btn btn-outline-success">
                            <i class="bi bi-cash-coin me-1"></i> {{ __('menu.pos_register') }}
                        </a>
                        <a href="{{ route('admin.kitchen-tickets.index') }}" class="btn btn-outline-warning">
                            <i class="bi bi-fire me-1"></i> {{ __('menu.kitchen_tickets') }}
                        </a>
                    </div>

                    <hr class="my-3">

                    <h6 class="text-muted">{{ __('menu.menu_categories') }}</h6>
                    @if($categories->isEmpty())
                        <div class="text-muted small">{{ __('pos.no_categories_yet') }}</div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($categories as $cat)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    {{ $cat->name }}
                                    <span class="badge bg-secondary rounded-pill">{{ $cat->items_count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
