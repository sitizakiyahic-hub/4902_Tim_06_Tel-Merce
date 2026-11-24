
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tel-Merce') | Tel-U E-Commerce</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>

<div class="app-shell" id="appShell">

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">T</div>
            <div>
                <h2>Tel-Merce</h2>
                <p>Tel-U E-Commerce</p>
            </div>
        </div>

        <div class="sidebar-menu">
            <p class="menu-label">MENU UTAMA</p>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}"
                   class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span>▦</span> Dashboard
                </a>
            @endif

            <a href="{{ route('produk.index') }}"
               class="menu-link {{ request()->routeIs('produk.*') ? 'active' : '' }}">
                <span>▣</span> Katalog Produk
            </a>

            <a href="{{ route('ekspedisi.index') }}"
               class="menu-link {{ request()->routeIs('ekspedisi.*') ? 'active' : '' }}">
                <span>➤</span> Ekspedisi
            </a>

            @if(auth()->user()->role === 'admin')
                <p class="menu-label">MANAJEMEN</p>

                <a href="{{ route('vouchers.index') }}"
                   class="menu-link {{ request()->routeIs('vouchers.*') ? 'active' : '' }}">
                    <span>◇</span> Voucher
                </a>

                <a href="{{ route('pelanggan.index') }}"
                   class="menu-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                    <span>♧</span> Pelanggan
                </a>

                <a href="{{ route('alamat.index') }}"
                   class="menu-link {{ request()->routeIs('alamat.*') ? 'active' : '' }}">
                    <span>⌖</span> Alamat Pengiriman
                </a>
            @else
                <p class="menu-label">AKUN SAYA</p>

                <a href="{{ route('pelanggan.index') }}"
                   class="menu-link {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
                    <span>♙</span> Profil Saya
                </a>

                <a href="{{ route('alamat.index') }}"
                   class="menu-link {{ request()->routeIs('alamat.*') ? 'active' : '' }}">
                    <span>⌖</span> Alamat Saya
                </a>
            @endif
        </div>

        <div class="sidebar-bottom">
            <div class="sidebar-user">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Pelanggan' }}</span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-logout">
                    Keluar dari akun
                </button>
            </form>
        </div>
    </aside>

    <div class="main-area">

        <header class="main-header">
            <div class="header-left">
                <button type="button" class="hamburger-btn"
                        id="sidebarToggle" aria-label="Tampilkan atau sembunyikan menu">
                    ☰
                </button>

                <div>
                    <h1>@yield('page-title', 'Selamat Datang')</h1>
                    <p>@yield('page-subtitle', 'Tel-U E-Commerce')</p>
                </div>
            </div>

            <div class="header-actions">
                <a href="{{ auth()->user()->role === 'admin' ? route('dashboard') : route('produk.index') }}"
                   class="home-btn">
                    <span>⌂</span> Home
                </a>

                <div class="header-role">
                    {{ auth()->user()->role === 'admin' ? 'ADMIN' : 'PELANGGAN' }}
                </div>
            </div>
        </header>

        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('pesan'))
                <div class="alert alert-success">{{ session('pesan') }}</div>
            @endif

            @yield('content')
        </main>
    </div>

</div>

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const appShell = document.getElementById('appShell');

    sidebarToggle.addEventListener('click', function () {
        appShell.classList.toggle('sidebar-hidden');
    });
</script>

</body>
</html>
