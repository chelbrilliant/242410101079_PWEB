<header>
  <img src="{{ asset('images/logo.png') }}" alt="Logo UPA UNEJ" width="46" onerror="this.style.display='none'" />
  <div>
    <h1>PustakaUPA</h1>
    <p>Sistem Informasi Peminjaman &amp; Pengembalian Buku UNEJ</p>
  </div>

  <div style="margin-left:auto; display:flex; align-items:center; gap:10px;">
    @auth
      {{-- Info user login --}}
      <div style="display:flex; align-items:center; gap:8px;">
        <div style="width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.25);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.9rem;color:white;">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
          <p style="margin:0;font-size:0.78rem;color:rgba(255,255,255,0.65);line-height:1;">Halo,</p>
          <p style="margin:0;font-size:0.88rem;font-weight:600;color:white;line-height:1.3;">
            {{ auth()->user()->name }}
            @if(auth()->user()->isAdmin())
              <span style="font-size:0.68rem;background:rgba(46,204,113,0.3);color:#2ecc71;padding:1px 7px;border-radius:10px;margin-left:4px;">Admin</span>
            @else
              <span style="font-size:0.68rem;background:rgba(46,134,193,0.3);color:#aed6f1;padding:1px 7px;border-radius:10px;margin-left:4px;">Customer</span>
            @endif
          </p>
        </div>
      </div>
      <a href="{{ route('profile.show') }}" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.3);padding:7px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;">
        Profil
      </a>
      <form method="POST" action="{{ route('logout') }}" style="margin:0;">
        @csrf
        <button type="submit" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.3);padding:7px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;cursor:pointer;font-family:var(--font-body);">
          Logout
        </button>
      </form>
    @else
      <a href="{{ route('login') }}" style="background:rgba(255,255,255,0.15);color:white;border:1px solid rgba(255,255,255,0.3);padding:7px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;">
        Login
      </a>
      <a href="{{ route('register') }}" style="background:#2ecc71;color:white;padding:7px 16px;border-radius:8px;font-size:0.82rem;font-weight:600;">
        Daftar
      </a>
    @endauth
  </div>
</header>

<hr />

<nav>
  <ul>
    <li><a href="{{ route('beranda') }}" class="{{ request()->routeIs('beranda') ? 'nav-active' : '' }}">Beranda</a></li>
    <li><a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'nav-active' : '' }}">Daftar Buku</a></li>
    @auth
      <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">Dashboard</a></li>
      <li><a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'nav-active' : '' }}">Peminjaman</a></li>
      <li><a href="{{ route('pengembalian.index') }}" class="{{ request()->routeIs('pengembalian.*') ? 'nav-active' : '' }}">Pengembalian</a></li>
    @endauth
    <li><a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'nav-active' : '' }}">Tentang</a></li>
  </ul>
</nav>

<hr />
