<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Sistem Informasi Perpustakaan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body>

    <header>
      <img src="{{ asset('images/logo.png') }}" alt="Logo Perpustakaan" width="80" onerror="this.style.display='none'" />
      <div>
        <h1>Sistem Informasi Perpustakaan</h1>
        <p>Manajemen Peminjaman dan Pengembalian Buku</p>
      </div>
    </header>

    <hr />

    <nav>
      <ul>
        <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'nav-active' : '' }}">Beranda</a></li>
        <li><a href="{{ route('peminjaman') }}" class="{{ request()->routeIs('peminjaman') ? 'nav-active' : '' }}">Peminjaman Buku</a></li>
        <li><a href="{{ route('daftar') }}" class="{{ request()->routeIs('daftar') ? 'nav-active' : '' }}">Daftar Buku</a></li>
        <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">Dashboard</a></li>
        <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'nav-active' : '' }}">Tentang</a></li>
        <li><a class="nav-disabled">Pengembalian Buku</a></li>
        <li><a class="nav-disabled">Statistik</a></li>
      </ul>
    </nav>

    <hr />

    @yield('content')

    <footer>
      <p>© 2026 | Sistem Informasi Perpustakaan | Chelsea Brilliant</p>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
  </body>
</html>
