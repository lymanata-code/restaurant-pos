@extends('admin.layouts.admin_layout')

@section('pageTitle', __('menu.dashboard'))
@section('breadcrumb_title', __('menu.dashboard'))
@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('menu.dashboard') }}</li>
@endsection

@section('content')
    <div class="row g-3">
        @php
            $cards = [
                ['title' => __('menu.branches'),    'value' => $stats['branches'],     'icon' => 'bi-shop',         'color' => 'primary'],
                ['title' => __('menu.menu_items'),  'value' => $stats['menu_items'],   'icon' => 'bi-journal-text', 'color' => 'success'],
                ['title' => __('menu.customers'),   'value' => $stats['customers'],    'icon' => 'bi-people',       'color' => 'warning'],
                ['title' => __('menu.orders'),      'value' => $stats['orders_today'], 'icon' => 'bi-receipt',      'color' => 'danger'],
            ];
        @endphp
        @foreach($cards as $c)
            <div class="col-12 col-md-6 col-xl-3">
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

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">{{ __('app.name') }}</h5>
            <p class="text-muted mb-0">
                Welcome to your Restaurant POS dashboard. Use the sidebar to navigate to
                Branches, Menu, Tables, Customers, Orders, Inventory, Purchasing, Accounting and
                System modules. The active branch is shown in the top header.
            </p>
        </div>
    </div>
@endsection
