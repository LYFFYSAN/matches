<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — World Cup 2026</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-icon">🔐</div>
        <h1 class="login-title">Admin Login</h1>
        <p class="login-subtitle">World Cup 2026 — Highlights Panel</p>

        @if($errors->has('secret'))
            <div class="alert alert-error">{{ $errors->first('secret') }}</div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="login-form">
            @csrf
            <div class="form-group">
                <label for="secret">Admin Secret</label>
                <input
                    type="password"
                    id="secret"
                    name="secret"
                    class="form-input"
                    placeholder="Enter admin password"
                    autofocus
                    required
                >
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login →</button>
        </form>

        <a href="{{ route('home') }}" class="login-back">← Back to site</a>
    </div>
</div>

</body>
</html>
