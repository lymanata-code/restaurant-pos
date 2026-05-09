@extends('admin.layouts.admin_layout')

@section('pageTitle', $isCreate ? __('branches.create_title') : __('branches.edit_title'))
@section('breadcrumb_title', __('branches.title'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.branches.index') }}">{{ __('branches.title') }}</a></li>
    <li class="breadcrumb-item active">
        {{ $isCreate ? __('branches.create_title') : __('branches.edit_title') }}
    </li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">
            {{ $isCreate ? __('branches.create_title') : __('branches.edit_title') }}
        </h5>

        <form method="POST"
              action="{{ $isCreate ? route('admin.branches.store') : route('admin.branches.update', $model->id) }}">
            @csrf
            @unless($isCreate) @method('PUT') @endunless

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('branches.code') }} <span class="text-danger">*</span></label>
                    <input name="code" type="text" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $model->code) }}" required maxlength="50">
                    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-9">
                    <label class="form-label">{{ __('branches.name') }} <span class="text-danger">*</span></label>
                    <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $model->name) }}" required maxlength="255">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('branches.phone') }}</label>
                    <input name="phone" type="text" class="form-control" value="{{ old('phone', $model->phone) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('branches.email') }}</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email', $model->email) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('branches.tax_number') }}</label>
                    <input name="tax_number" type="text" class="form-control" value="{{ old('tax_number', $model->tax_number) }}">
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('branches.address') }}</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $model->address) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('branches.receipt_header') }}</label>
                    <textarea name="receipt_header" class="form-control" rows="3">{{ old('receipt_header', $model->receipt_header) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('branches.receipt_footer') }}</label>
                    <textarea name="receipt_footer" class="form-control" rows="3">{{ old('receipt_footer', $model->receipt_footer) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('branches.refund_policy') }}</label>
                    <textarea name="refund_policy" class="form-control" rows="2">{{ old('refund_policy', $model->refund_policy) }}</textarea>
                </div>

                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $model->exists ? $model->is_active : true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">{{ __('branches.is_active') }}</label>
                    </div>
                </div>
            </div>

            @include('admin._partials.form_buttons', ['routeName' => 'admin.branches'])
        </form>
    </div>
</div>
@endsection
