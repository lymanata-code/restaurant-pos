@include('admin.layouts.admin_partials.head')

<body>
    <div class="wrapper">
        @include('admin.layouts.admin_partials.header')
        @include('admin.layouts.admin_partials.left_sidebar')

        <main class="page-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">@yield('breadcrumb_title', __('menu.dashboard'))</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    @yield('toolbar')
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <div class="overlay nav-toggle-icon"></div>
        <a href="javascript:;" class="back-to-top" style="display:none;"><i class="bi bi-arrow-up"></i></a>
    </div>

    @include('admin.layouts.admin_partials.scripts')
</body>
</html>
