{{-- Reusable CRUD index — extended by per-module index views via @include or via @section('table'). --}}
@extends('admin.layouts.admin_layout')

@section('pageTitle', __($translationNamespace . '.title'))
@section('breadcrumb_title', __($translationNamespace . '.title'))
@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __($translationNamespace . '.title') }}</li>
@endsection

@section('toolbar')
    @yield('toolbar_extra')
    @if(\Route::has($routeName . '.create'))
        <a href="{{ route($routeName . '.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>{{ __('common.create') }}
        </a>
    @endif
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">{{ __($translationNamespace . '.title') }}</h5>
            @yield('filters')

            <div class="table-responsive">
                <table id="dt" class="table table-striped table-hover w-100">
                    <thead>
                        <tr>
                            @yield('thead')
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const $ = window.$;
    const ajaxUrl = @json(route($routeName . '.index'));

    const table = $('#dt').DataTable({
        ajax: { url: ajaxUrl },
        columns: @yield('columns_json'),
        order: @hasSection('default_order')@yield('default_order')@else[[0, "desc"]]@endif,
    });
});
</script>
@endpush
