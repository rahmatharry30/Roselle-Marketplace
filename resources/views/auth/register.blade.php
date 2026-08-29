<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
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
            <div class="auth-title">SIGN UP</div>

            <form class="auth-form" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="auth-field">
                    <div class="auth-field-icon">👤</div>
                    <input type="text" name="name" placeholder="NAMA LENGKAP" value="{{ old('name') }}" required autofocus>
                </div>
                @error('name')
                    <div class="auth-error">{{ $message }}</div>
                @enderror

                <div class="auth-field">
                    <div class="auth-field-icon">✉️</div>
                    <input type="email" name="email" placeholder="EMAIL" value="{{ old('email') }}" required>
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

                <div class="auth-field">
                    <div class="auth-field-icon">🔒</div>
                    <input type="password" name="password_confirmation" placeholder="KONFIRMASI PASSWORD" required>
                </div>

                <div class="auth-field">
                    <div class="auth-field-icon">🛍️</div>
                    <select name="role" class="auth-select" required>
                        <option value="buyer">Daftar sebagai Pembeli</option>
                        <option value="seller">Daftar sebagai Penjual</option>
                    </select>
                </div>
                @error('role')
                    <div class="auth-error">{{ $message }}</div>
                @enderror

                <div class="auth-switch">
                    Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                </div>

                <button type="submit" class="auth-submit">SIGN UP</button>
            </form>

            <div class="auth-or">Atau daftar menggunakan:</div>
            <div class="auth-socials">
                <div>🔍</div>
                <div>📷</div>
                <div>🎵</div>
            </div>
        </div>
    </div>
</body>
</html>
