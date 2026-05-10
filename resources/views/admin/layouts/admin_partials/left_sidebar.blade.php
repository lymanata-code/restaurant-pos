@php
    /**
     * Each entry: ['title' => 'menu.key', 'icon' => 'bi-...', 'route' => optional, 'children' => [..]].
     * `route` accepts either a route name OR a closure returning a URL.
     */
    $sections = [
        [
            'title' => 'menu.dashboard',
            'icon' => 'bi-house-door',
            'route' => 'admin.dashboard',
        ],
        [
            'title' => 'menu.pos',
            'icon' => 'bi-cart3',
            'children' => [
                ['title' => 'menu.pos_terminal', 'route' => 'admin.pos.terminal'],
                ['title' => 'menu.pos_register', 'route' => 'admin.pos.register'],
            ],
        ],
        [
            'title' => 'menu.system',
            'icon' => 'bi-gear',
            'children' => [
                ['title' => 'menu.branches',         'route' => 'admin.branches.index'],
                ['title' => 'menu.tax_rates',        'route' => 'admin.tax-rates.index'],
                ['title' => 'menu.payment_methods',  'route' => 'admin.payment-methods.index'],
                ['title' => 'menu.printers',         'route' => 'admin.printers.index'],
                ['title' => 'menu.code_sequences',   'route' => 'admin.code-sequences.index'],
            ],
        ],
        [
            'title' => 'menu.rbac',
            'icon' => 'bi-shield-lock',
            'children' => [
                ['title' => 'menu.users',           'route' => 'admin.users.index'],
                ['title' => 'menu.staff',           'route' => 'admin.staff.index'],
                ['title' => 'menu.roles',           'route' => 'admin.roles.index'],
                ['title' => 'menu.permissions',     'route' => 'admin.permissions.index'],
                ['title' => 'menu.login_histories', 'route' => 'admin.login-histories.index'],
            ],
        ],
        [
            'title' => 'menu.menu_management',
            'icon' => 'bi-journal-text',
            'children' => [
                ['title' => 'menu.menu_categories', 'route' => 'admin.menu-categories.index'],
                ['title' => 'menu.menu_items',      'route' => 'admin.menu-items.index'],
                ['title' => 'menu.modifier_groups', 'route' => 'admin.modifier-groups.index'],
                ['title' => 'menu.modifiers',       'route' => 'admin.modifiers.index'],
            ],
        ],
        [
            'title' => 'menu.tables',
            'icon' => 'bi-grid-3x3-gap',
            'children' => [
                ['title' => 'menu.zones',           'route' => 'admin.zones.index'],
                ['title' => 'menu.dining_tables',   'route' => 'admin.dining-tables.index'],
            ],
        ],
        [
            'title' => 'menu.customers',
            'icon' => 'bi-people',
            'route' => 'admin.customers.index',
        ],
        [
            'title' => 'menu.orders',
            'icon' => 'bi-receipt',
            'route' => 'admin.orders.index',
        ],
        [
            'title' => 'menu.kitchen',
            'icon' => 'bi-fire',
            'children' => [
                ['title' => 'menu.kitchen_stations', 'route' => 'admin.kitchen-stations.index'],
                ['title' => 'menu.kitchen_tickets',  'route' => 'admin.kitchen-tickets.index'],
            ],
        ],
        [
            'title' => 'menu.promotions',
            'icon' => 'bi-tags',
            'children' => [
                ['title' => 'menu.promotions', 'route' => 'admin.promotions.index'],
                ['title' => 'menu.coupons',    'route' => 'admin.coupons.index'],
            ],
        ],
        [
            'title' => 'menu.inventory',
            'icon' => 'bi-box-seam',
            'children' => [
                ['title' => 'menu.inventory_categories', 'route' => 'admin.inventory-categories.index'],
                ['title' => 'menu.units',                'route' => 'admin.units.index'],
                ['title' => 'menu.stock_items',          'route' => 'admin.stock-items.index'],
                ['title' => 'menu.stock_movements',      'route' => 'admin.stock-movements.index'],
                ['title' => 'menu.stock_adjustments',    'route' => 'admin.stock-adjustments.index'],
            ],
        ],
        [
            'title' => 'menu.purchasing',
            'icon' => 'bi-truck',
            'children' => [
                ['title' => 'menu.suppliers',        'route' => 'admin.suppliers.index'],
                ['title' => 'menu.purchase_orders',  'route' => 'admin.purchase-orders.index'],
                ['title' => 'menu.goods_receives',   'route' => 'admin.goods-receives.index'],
            ],
        ],
        [
            'title' => 'menu.accounting',
            'icon' => 'bi-cash-coin',
            'children' => [
                ['title' => 'menu.accounts',         'route' => 'admin.accounts.index'],
                ['title' => 'menu.expenses',         'route' => 'admin.expenses.index'],
                ['title' => 'menu.journal_entries',  'route' => 'admin.journal-entries.index'],
            ],
        ],
        [
            'title' => 'menu.reports',
            'icon' => 'bi-bar-chart',
            'children' => [
                ['title' => 'menu.audit_logs',     'route' => 'admin.audit-logs.index'],
                ['title' => 'menu.notifications',  'route' => 'admin.notifications.index'],
            ],
        ],
    ];

    $isActive = function ($routeName) {
        if (!$routeName) return false;
        return request()->routeIs($routeName) || request()->routeIs(str_replace('.index', '.*', $routeName));
    };
    $hasActiveChild = function (array $section) use ($isActive) {
        foreach ($section['children'] ?? [] as $child) {
            if ($isActive($child['route'])) return true;
        }
        return false;
    };
@endphp

<aside class="sidebar-wrapper">
    <div class="sidebar-header">
        <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                 style="width: 32px; height: 32px;">
                <i class="bi bi-shop"></i>
            </div>
            <h4 class="logo-text">Restaurant POS</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-chevron-double-left"></i></div>
    </div>

    <ul class="metismenu" id="menu">
        @foreach($sections as $section)
            @php $hasChildren = !empty($section['children']); @endphp
            <li class="@if($hasChildren && $hasActiveChild($section)) mm-active @endif">
                <a href="{{ $hasChildren ? 'javascript:;' : (\Route::has($section['route'] ?? '') ? route($section['route']) : 'javascript:;') }}"
                   class="@if($hasChildren) has-arrow @endif @if(!$hasChildren && $isActive($section['route'])) active @endif">
                    <div class="parent-icon"><i class="bi {{ $section['icon'] }}"></i></div>
                    <div class="menu-title">{{ __($section['title']) }}</div>
                </a>
                @if($hasChildren)
                    <ul style="display: {{ $hasActiveChild($section) ? 'block' : 'none' }};">
                        @foreach($section['children'] as $child)
                            <li>
                                <a href="{{ \Route::has($child['route']) ? route($child['route']) : 'javascript:;' }}"
                                   class="@if($isActive($child['route'])) active @endif">
                                    <i class="bi bi-arrow-right-short"></i>{{ __($child['title']) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</aside>
