@php
    /** @var string $appLocale */
    $appLocale = app()->getLocale();
@endphp
<!doctype html>
<html lang="{{ $appLocale }}" class="minimal-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-locale" content="{{ $appLocale }}">
    <meta name="current-branch-id" content="{{ session('current_branch_id') }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&amp;display=swap" rel="stylesheet">

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <title>@yield('pageTitle', config('app.name'))</title>

    @stack('styles')
</head>
