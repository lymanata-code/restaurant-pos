{{--
    Reusable Yajra DataTable "Actions" column renderer.
    Pass: $row, $routeName.
--}}
@php
    $editRoute = \Route::has($routeName . '.edit') ? route($routeName . '.edit', $row->id) : null;
    $deleteRoute = \Route::has($routeName . '.destroy') ? route($routeName . '.destroy', $row->id) : null;
@endphp
<div class="d-inline-flex gap-1">
    @if($editRoute)
        <a href="{{ $editRoute }}" class="btn btn-sm btn-outline-primary"
           title="{{ __('common.edit') }}">
            <i class="bi bi-pencil"></i>
        </a>
    @endif
    @if($deleteRoute)
        <button type="button" class="btn btn-sm btn-outline-danger"
                data-confirm-delete data-url="{{ $deleteRoute }}"
                title="{{ __('common.delete') }}">
            <i class="bi bi-trash"></i>
        </button>
    @endif
</div>
