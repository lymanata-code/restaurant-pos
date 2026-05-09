@php
    use App\Support\BranchContext;
    /** @var \App\Models\Restaurant|null $currentBranch */
    $currentBranch = BranchContext::current();
    /** @var \Illuminate\Support\Collection<\App\Models\Restaurant> $branches */
    $branches = BranchContext::availableBranches();
    $authUser = auth()->user();
    $locale = app()->getLocale();
@endphp
<header class="top-header">
    <nav class="navbar navbar-expand">
        <div class="mobile-toggle-icon d-xl-none">
            <i class="bi bi-list"></i>
        </div>

        <div class="branch-switcher d-none d-md-flex align-items-center ms-2">
            <i class="bi bi-shop me-2 text-muted"></i>
            <form method="POST" action="{{ route('admin.branches.switch') }}" class="d-flex align-items-center">
                @csrf
                <select name="restaurant_id" class="form-select form-select-sm"
                        onchange="this.form.submit()" data-tomselect data-placeholder="@lang('app.current_branch')">
                    @if(auth()->user()?->isSuperAdmin())
                        <option value="">{{ __('app.all_branches') }}</option>
                    @endif
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" @selected(optional($currentBranch)->id === $b->id)>
                            {{ $b->name }} ({{ $b->code }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <form class="searchbar d-none d-xl-flex ms-3" onsubmit="return false;">
            <div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
            <input class="form-control" type="text" placeholder="{{ __('common.search') }}…">
        </form>

        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
                {{-- Language switcher (no reload) --}}
                <li class="nav-item dropdown lang-switcher me-2">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret d-flex align-items-center"
                       href="#" data-bs-toggle="dropdown">
                        <span class="lang-flag {{ $locale === 'km' ? 'km' : 'en' }}">
                            {{ $locale === 'km' ? 'ខ្មែរ' : 'EN' }}
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#" data-set-locale="en">
                                <span class="lang-flag en">EN</span> English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="#" data-set-locale="km">
                                <span class="lang-flag km">ខ្មែរ</span> ខ្មែរ
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- User dropdown --}}
                <li class="nav-item dropdown dropdown-large">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                        <div class="user-setting d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                 style="width: 36px; height: 36px;">
                                {{ strtoupper(mb_substr($authUser?->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="user-name d-none d-sm-block">
                                <div class="fw-semibold">{{ $authUser?->name }}</div>
                                <small class="text-muted">{{ $authUser?->roles?->pluck('name')->join(', ') }}</small>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer me-2"></i>{{ __('menu.dashboard') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i>{{ __('app.logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
