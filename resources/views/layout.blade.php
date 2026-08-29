<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Marketplace')</title>
    <script>
        (function() {
            const saved = localStorage.getItem('roseTheme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <nav class="rose-nav">
        <a href="{{ route('products.index') }}" class="rose-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Roselle Fashion Bag">
        </a>

        <form action="{{ route('products.index') }}" method="GET" class="rose-nav-search">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk...">
            <button type="submit">🔍</button>
        </form>

        <div class="rose-nav-links">
            <a href="{{ route('products.index') }}">Home</a>
            <a href="{{ route('categories.index') }}">Kategori</a>
        </div>

        <div class="rose-nav-icons">
            @auth
                @if (auth()->user()->isSeller())
                    <a href="{{ route('dashboard') }}" class="rose-icon-link">
                        <span class="icon">📊</span> Dashboard
                    </a>
                    <a href="{{ route('orders.sellerIndex') }}" class="rose-icon-link">
                        <span class="icon">📦</span> Pesanan
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="rose-icon-link">
                        <span class="icon">📊</span> Dashboard
                    </a>
                    <a href="{{ route('orders.index') }}" class="rose-icon-link">
                        <span class="icon">📦</span> Pesanan
                    </a>
                    <a href="{{ route('cart.index') }}" class="rose-icon-link">
                        <span class="icon">🛒</span> Keranjang
                    </a>
                @endif

                <div class="rose-account">
                    <button type="button" class="rose-account-btn" onclick="roseToggleAccountMenu()">
                        👤 Account
                    </button>

                    <div class="rose-account-menu" id="roseAccountMenu">
                        <div class="rose-account-menu-header">
                            <div class="rose-account-menu-name">{{ auth()->user()->name }}</div>
                            <div class="rose-account-menu-role">{{ auth()->user()->role }}</div>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="rose-account-menu-item">
                            ✏️ Edit Profil
                        </a>

                        <button type="button" class="rose-account-menu-item" onclick="roseToggleTheme()">
                            <span id="roseThemeIcon">🌙</span> <span id="roseThemeLabel">Mode Gelap</span>
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rose-account-menu-item danger">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="rose-icon-link">
                    <span class="icon">👤</span> Login
                </a>
                <a href="{{ route('register') }}" class="rose-icon-link">Register</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <p style="color:green;">{{ session('success') }}</p>
        @endif

        @yield('content')
    </div>

    <script>
        function roseToggleAccountMenu() {
            document.getElementById('roseAccountMenu').classList.toggle('open');
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('roseAccountMenu');
            const btn = document.querySelector('.rose-account-btn');
            if (menu && !menu.contains(e.target) && e.target !== btn) {
                menu.classList.remove('open');
            }
        });

        function roseToggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('roseTheme', next);
            roseSyncThemeIcon();
        }

        function roseSyncThemeIcon() {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const icon = document.getElementById('roseThemeIcon');
            const label = document.getElementById('roseThemeLabel');
            if (icon) icon.textContent = isDark ? '☀️' : '🌙';
            if (label) label.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
        }

        roseSyncThemeIcon();
    </script>
</body>
</html>
