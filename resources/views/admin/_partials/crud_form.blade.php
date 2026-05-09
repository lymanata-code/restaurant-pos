@extends('admin.layouts.admin_layout')

@section('pageTitle', $isCreate ? __($translationNamespace . '.create_title') : __($translationNamespace . '.edit_title'))
@section('breadcrumb_title', __($translationNamespace . '.title'))
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route($routeName . '.index') }}">{{ __($translationNamespace . '.title') }}</a></li>
    <li class="breadcrumb-item active">
        {{ $isCreate ? __($translationNamespace . '.create_title') : __($translationNamespace . '.edit_title') }}
    </li>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title mb-3">
            {{ $isCreate ? __($translationNamespace . '.create_title') : __($translationNamespace . '.edit_title') }}
        </h5>

        <form method="POST"
              action="{{ $isCreate ? route($routeName . '.store') : route($routeName . '.update', $model->getKey()) }}">
            @csrf
            @unless($isCreate) @method('PUT') @endunless

            <div class="row g-3">
            @foreach($fields as $name => $cfg)
                @php
                    $type = $cfg['type'] ?? 'text';
                    $label = __($translationNamespace . '.' . ($cfg['label'] ?? $name));
                    $col = $cfg['col'] ?? 'md-6';
                    $required = !empty($cfg['required']);
                    $value = old($name, data_get($model, $name, $cfg['default'] ?? null));
                @endphp

                @if($type === 'hidden')
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @elseif($type === 'checkbox')
                    <div class="col-{{ $col }}">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="f_{{ $name }}"
                                   name="{{ $name }}" value="1"
                                   {{ old($name, $model->exists ? data_get($model, $name) : ($cfg['default'] ?? false)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="f_{{ $name }}">{{ $label }}</label>
                        </div>
                    </div>
                @elseif($type === 'textarea')
                    <div class="col-{{ $col }}">
                        <label class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
                        <textarea name="{{ $name }}" rows="{{ $cfg['rows'] ?? 3 }}"
                                  class="form-control @error($name) is-invalid @enderror"
                                  @if($required) required @endif>{{ $value }}</textarea>
                        @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @elseif($type === 'select')
                    <div class="col-{{ $col }}">
                        <label class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
                        <select name="{{ $name }}"
                                class="form-select @error($name) is-invalid @enderror"
                                data-tomselect
                                data-placeholder="{{ $cfg['placeholder'] ?? __('common.select') }}"
                                @if(!empty($cfg['multiple'])) multiple @endif
                                @if($required) required @endif>
                            @if(empty($cfg['multiple']))
                                <option value="">— {{ __('common.select') }} —</option>
                            @endif
                            @foreach(($cfg['options'] ?? []) as $optVal => $optLabel)
                                <option value="{{ $optVal }}"
                                        @selected((string) $value === (string) $optVal)>
                                    {{ $optLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @elseif(in_array($type, ['datetime','date','time']))
                    <div class="col-{{ $col }}">
                        <label class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
                        <input type="text" name="{{ $name }}"
                               class="form-control @error($name) is-invalid @enderror"
                               value="{{ $value }}"
                               data-flatpickr
                               @if($type === 'datetime') data-datetime @elseif($type === 'time') data-time @endif
                               @if($required) required @endif>
                        @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @else
                    <div class="col-{{ $col }}">
                        <label class="form-label">{{ $label }} @if($required)<span class="text-danger">*</span>@endif</label>
                        <input type="{{ $type }}" name="{{ $name }}"
                               class="form-control @error($name) is-invalid @enderror"
                               value="{{ $value }}"
                               @if(!empty($cfg['maxlength'])) maxlength="{{ $cfg['maxlength'] }}" @endif
                               @if(!empty($cfg['step'])) step="{{ $cfg['step'] }}" @endif
                               @if($required) required @endif>
                        @error($name) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                @endif
            @endforeach
            </div>

            @include('admin._partials.form_buttons', ['routeName' => $routeName])
        </form>
    </div>
</div>
@endsection
