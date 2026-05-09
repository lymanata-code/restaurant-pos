@php
    $cancelUrl = $cancelUrl ?? route($routeName . '.index');
@endphp
<div class="d-flex justify-content-end gap-2 mt-4">
    <a href="{{ $cancelUrl }}" class="btn btn-secondary">
        <i class="bi bi-x-lg me-1"></i>{{ __('common.cancel') }}
    </a>
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save me-1"></i>{{ __('common.save') }}
    </button>
</div>
