<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '🔐 Admin — World Cup 2026')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body">

<nav class="navbar admin-nav">
    <div class="navbar-inner">
        <a href="{{ route('admin.index') }}" class="navbar-brand">
            🔐 <span>Admin Panel</span>
        </a>
        <div class="navbar-links">
            <a href="{{ route('home') }}" target="_blank">View Site ↗</a>
            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </div>
</nav>

<main class="main-content admin-main">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
