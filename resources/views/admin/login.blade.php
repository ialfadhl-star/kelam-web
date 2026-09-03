<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin — KELAM</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/kelam.css') }}">
</head>
<body>
    <div class="admin-login-wrap">
        <div class="admin-login-card">
            <a href="{{ route('home') }}" class="brand">KE<span>L</span>AM</a>
            <p class="sub">Panel Admin</p>

            @if($errors->any())
                <div class="flash flash-error">{{ $errors->first() }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.login.attempt') }}" method="post">
                @csrf
                <div class="field">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="field" style="display:flex; align-items:center; gap:8px;">
                    <input type="checkbox" name="remember" id="remember" style="width:auto;">
                    <label for="remember" style="margin:0; text-transform:none; letter-spacing:0;">Ingat saya</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Masuk</button>
            </form>
        </div>
    </div>
</body>
</html>
