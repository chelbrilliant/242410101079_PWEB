<header>
  <img src="{{ asset('images/logo.png') }}" alt="Logo Perpustakaan" width="80" onerror="this.style.display='none'" />
  <div>
    <h1>Sistem Informasi Perpustakaan</h1>
    <p>Manajemen Peminjaman dan Pengembalian Buku</p>
  </div>

  <div style="margin-left:auto; display:flex; align-items:center; gap:10px;">

    {{-- SOAL 3c — Tombol Toggle Dark Mode --}}
    <button id="darkModeToggle"
            onclick="toggleDarkMode()"
            title="Toggle Dark Mode"
            style="background:rgba(255,255,255,0.15); color:white; border:1px solid rgba(255,255,255,0.3); padding:7px 14px; border-radius:8px; font-size:0.85rem; font-weight:600; cursor:pointer; transition: all 0.2s;">
      🌙 Dark
    </button>

    {{-- Link Preferensi --}}
    <a href="{{ route('preferensi') }}"
       style="background:rgba(255,255,255,0.1); color:white; border:1px solid rgba(255,255,255,0.2); padding:7px 14px; border-radius:8px; font-size:0.82rem; font-weight:600;">
      ⚙ Preferensi
    </a>

    @auth
      <div style="display:flex; align-items:center; gap:8px;">
        <div style="width:34px; height:34px; border-radius:50%; background:rgba(255,255,255,0.25); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.9rem; color:white;">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
          <p style="margin:0; font-size:0.82rem; color:rgba(255,255,255,0.75); line-height:1;">Halo,</p>
          <p style="margin:0; font-size:0.9rem; font-weight:600; color:white; line-height:1.3;">
            {{ auth()->user()->name }}
            @if(auth()->user()->role === 'admin')
              <span style="font-size:0.7rem; background:rgba(46,204,113,0.3); color:#2ecc71; padding:1px 7px; border-radius:10px; margin-left:4px;">Admin</span>
            @endif
          </p>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit"
                style="background:rgba(255,255,255,0.15); color:white; border:1px solid rgba(255,255,255,0.3); padding:7px 16px; border-radius:8px; font-size:0.82rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
          Logout
        </button>
      </form>
    @else
      <a href="{{ route('login') }}"
         style="background:rgba(255,255,255,0.15); color:white; border:1px solid rgba(255,255,255,0.3); padding:7px 16px; border-radius:8px; font-size:0.82rem; font-weight:600;">
        Login
      </a>
      <a href="{{ route('register') }}"
         style="background:#2ecc71; color:white; padding:7px 16px; border-radius:8px; font-size:0.82rem; font-weight:600;">
        Register
      </a>
    @endauth
  </div>
</header>

<hr />

<nav>
  <ul>
    <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'nav-active' : '' }}">Beranda</a></li>
    @auth
      <li><a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'nav-active' : '' }}">Peminjaman Buku</a></li>
    @endauth
    <li><a href="{{ route('daftar') }}" class="{{ request()->routeIs('daftar') ? 'nav-active' : '' }}">Daftar Buku</a></li>
    @auth
      <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">Dashboard</a></li>
    @endauth
    <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'nav-active' : '' }}">Tentang</a></li>
    <li><a href="{{ route('kontak') }}" class="{{ request()->routeIs('kontak') ? 'nav-active' : '' }}">Kontak</a></li>
    <li><a href="{{ route('kunjungan') }}" class="{{ request()->routeIs('kunjungan*') ? 'nav-active' : '' }}">📊 Kunjungan</a></li>
    @auth
      @if(auth()->user()->role === 'admin')
        <li><a href="{{ route('admin.statistik') }}" class="{{ request()->routeIs('admin.*') ? 'nav-active' : '' }}" style="color:#2ecc71;">⚙ Admin</a></li>
      @endif
    @endauth
    <li><a class="nav-disabled">Pengembalian Buku</a></li>
    <li><a class="nav-disabled">Statistik</a></li>
  </ul>
</nav>

<hr />

{{-- SOAL 3c — Script Toggle Dark Mode --}}
<script>
  function toggleDarkMode() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    // Simpan ke cookie
    setCookie('tema', isDark ? 'dark' : 'light', 30);
    updateDarkBtn(isDark);
  }

  function updateDarkBtn(isDark) {
    const btn = document.getElementById('darkModeToggle');
    if (!btn) return;
    btn.textContent = isDark ? '☀️ Light' : '🌙 Dark';
  }

  // Set label tombol sesuai kondisi saat load
  document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.classList.contains('dark');
    updateDarkBtn(isDark);
  });
</script>
