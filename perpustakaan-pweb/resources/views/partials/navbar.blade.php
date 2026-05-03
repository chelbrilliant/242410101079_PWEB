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
    <li><a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'nav-active' : '' }}">Kontak</a></li>
    <li><a class="nav-disabled">Pengembalian Buku</a></li>
    <li><a class="nav-disabled">Statistik</a></li>
  </ul>
</nav>

<hr />
