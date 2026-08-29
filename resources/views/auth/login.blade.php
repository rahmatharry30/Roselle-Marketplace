<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="auth-page">
        <div class="auth-stripes">
            <div class="stripe-1"></div>
            <div class="stripe-2"></div>
            <div class="stripe-3"></div>
            <div class="stripe-4"></div>
        </div>

        <div class="auth-panel">
            <div class="auth-avatar">👤</div>
            <div class="auth-title">LOGIN</div>

            @if (session('status'))
                <div class="auth-error" style="color:#86efac;">{{ session('status') }}</div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="auth-field">
                    <div class="auth-field-icon">👤</div>
                    <input type="email" name="email" placeholder="EMAIL" value="{{ old('email') }}" required autofocus>
                </div>
                @error('email')
                    <div class="auth-error">{{ $message }}</div>
                @enderror

                <div class="auth-field">
                    <div class="auth-field-icon">🔒</div>
                    <input type="password" name="password" placeholder="PASSWORD" required>
                </div>
                @error('password')
                    <div class="auth-error">{{ $message }}</div>
                @enderror

                <div class="auth-remember">
                    <label style="display:flex; align-items:center; gap:6px;">
                        <input type="checkbox" name="remember" style="width:auto; margin:0;">
                        Ingat saya
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Lupa password?</a>
                    @endif
                </div>

                <div class="auth-switch">
                    Belum punya akun? <a href="{{ route('register') }}">Sign Up</a>
                </div>

                <button type="submit" class="auth-submit">LOG-IN</button>
            </form>

            <div class="auth-or">Atau masuk menggunakan:</div>
            <div class="auth-socials">
                <div>🔍</div>
                <div>📷</div>
                <div>🎵</div>
            </div>
        </div>
    </div>
</body>
</html>
