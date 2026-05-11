@extends('admin.layouts.admin_layout')

@section('pageTitle', __('menu.pos_terminal'))
@section('breadcrumb_title', __('menu.pos'))
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('menu.pos') }}</li>
    <li class="breadcrumb-item active">{{ __('menu.pos_terminal') }}</li>
@endsection

@php
    $currency = config('pos.currency_symbol', '$');

    $payload = [
        'currency'   => $currency,
        'openTabs'   => $stats['open_tabs'] ?? 0,
        'links'      => [
            'register' => route('admin.pos.register'),
            'kitchen'  => route('admin.kitchen-tickets.index'),
            'orders'   => route('admin.orders.index'),
        ],
        'priceOptions' => collect($priceOptions)
            ->mapWithKeys(fn ($slug, $key) => [$key => __('pos.price_options.' . $slug)])
            ->all(),
        'customers'  => $customers->map(fn ($c) => [
            'id'    => $c->id,
            'name'  => $c->name,
            'phone' => $c->phone,
        ])->values(),
        'categories' => $categories->map(fn ($c) => [
            'id'          => $c->id,
            'name'        => $c->name,
            'items_count' => $c->items_count,
            'image'       => $c->image_path ? asset('storage/' . $c->image_path) : null,
        ])->values(),
        'items' => $items->map(fn ($i) => [
            'id'          => $i->id,
            'name'        => $i->name,
            'code'        => $i->item_code,
            'price'       => (float) $i->sale_price,
            'image'       => $i->image_path ? asset('storage/' . $i->image_path) : null,
            'category_id' => $i->category_id,
        ])->values(),
        'featured' => $featuredItems->map(fn ($i) => [
            'id'          => $i->id,
            'name'        => $i->name,
            'code'        => $i->item_code,
            'price'       => (float) $i->sale_price,
            'image'       => $i->image_path ? asset('storage/' . $i->image_path) : null,
            'category_id' => $i->category_id,
        ])->values(),
    ];
@endphp

@section('content')
    <div id="app" data-page="pos.terminal" class="pos-terminal-mount">
        <div class="text-center py-5 text-muted">
            <div class="spinner-border spinner-border-sm me-2"></div>
            {{ __('pos.loading_terminal') }}
        </div>
    </div>

    <script type="application/json" id="pos-bootstrap-data">
        {!! json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endsection
