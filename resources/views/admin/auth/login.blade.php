@php $locale = app()->getLocale(); @endphp
<!doctype html>
<html lang="{{ $locale }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ __('auth.login_title') }} — {{ config('app.name') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
  @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">
  <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-sm" style="width: 420px; max-width: 92%;">
      <div class="card-body p-4">
        <div class="text-center mb-4">
          <i class="bi bi-shop fs-2 text-primary"></i>
          <h4 class="mt-2 mb-0 fw-bold">{{ config('app.name') }}</h4>
          <small class="text-muted">{{ __('auth.login_title') }}</small>
        </div>

        <div class="d-flex justify-content-end mb-3">
          <a href="#" class="btn btn-sm btn-outline-secondary"
            data-set-locale="{{ $locale === 'en' ? 'km' : 'en' }}">
            {{ $locale === 'en' ? 'ខ្មែរ' : 'English' }}
          </a>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">{{ __('auth.username_or_email') }}</label>
            <input name="login" type="text" class="form-control" autofocus required value="{{ old('login') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">{{ __('auth.password') }}</label>
            <input name="password" type="password" class="form-control" required>
          </div>
          <div class="form-check mb-3">
            <input id="remember" name="remember" type="checkbox" class="form-check-input" value="1">
            <label for="remember" class="form-check-label">{{ __('auth.remember_me') }}</label>
          </div>
          <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('auth.sign_in') }}
          </button>
        </form>

        <div class="text-center small text-muted mt-3">
          Default credentials: <code>admin / admin@123</code>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
