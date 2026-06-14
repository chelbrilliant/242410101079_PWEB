@extends('layouts.app')
@section('title', 'Beranda — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  {{-- Hero --}}
  <div class="beranda-hero">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Sistem Informasi Perpustakaan UPA UNEJ</div>
      <h2>Selamat Datang di<br/>Perpustakaan UPA UNEJ</h2>

      {{-- Cookie: tampilkan "Selamat datang kembali" jika ada cookie --}}
      @if($lastUserName)
        <p style="color:#7dffbe; font-size:0.9rem; margin-bottom:6px; font-weight:600;">
          Selamat datang kembali, <strong>{{ $lastUserName }}</strong>!
        </p>
      @endif

      <p>Kelola peminjaman dan pengembalian buku dengan mudah, cepat, dan efisien. Tersedia {{ $totalBuku }} judul buku siap dipinjam.</p>
      <div class="beranda-cta">
        @auth
          <a href="{{ route('peminjaman.create') }}" class="cta-btn cta-primary">Pinjam Buku</a>
          <a href="{{ route('dashboard') }}" class="cta-btn cta-secondary">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="cta-btn cta-primary">Login</a>
          <a href="{{ route('register') }}" class="cta-btn cta-secondary">Daftar Sekarang</a>
        @endauth
      </div>
    </div>
    <div class="beranda-hero-art">
    </div>
  </div>

  {{-- Stat Cards -- data dari controller (database real) --}}

  @if(auth()->check() && auth()->user()->isAdmin())

  <div class="beranda-stats" style="padding: 20px 20px; max-width:1100px; margin: 20px auto 20px;">
    <div class="bstat-card">
      <div class="bstat-icon">📖</div>
      <div class="bstat-num">{{ $totalBuku }}</div>
      <div class="bstat-label">Total Buku</div>
    </div>
    <div class="bstat-card">
      <div class="bstat-icon">📋</div>
      <div class="bstat-num">{{ $totalPeminjaman }}</div>
      <div class="bstat-label">Total Peminjaman</div>
    </div>
    <div class="bstat-card">
      <div class="bstat-icon">🔄</div>
      <div class="bstat-num">{{ $sedangDipinjam }}</div>
      <div class="bstat-label">Sedang Dipinjam</div>
    </div>
    <div class="bstat-card {{ $terlambat > 0 ? 'bstat-warn' : '' }}">
      <div class="bstat-icon">⚠️</div>
      <div class="bstat-num">{{ $terlambat }}</div>
      <div class="bstat-label">Terlambat</div>
    </div>
  </div>

  {{-- Live Search AJAX — hanya untuk yang sudah login --}}
  @auth
  <div style="padding: 0 20px 32px; max-width:1100px; margin: 0 auto;">
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:16px 24px;">
        <h3 style="color:white;margin:0;font-size:1rem;">Cari Peminjaman</h3>
      </div>
      <div style="padding:24px;">
        <div style="display:flex;gap:12px;margin-bottom:16px;">
          <input type="text" id="searchInput"
                 placeholder="Cari nama peminjam, judul buku, ID anggota, atau status..."
                 style="flex:1;padding:11px 16px;border:1.5px solid var(--border);border-radius:8px;font-size:0.92rem;font-family:var(--font-body);outline:none;"
                 oninput="liveSearch(this.value)" />
          <button onclick="resetSearch()"
                  style="background:#f0f4f8;color:var(--text);padding:11px 18px;border:none;border-radius:8px;font-size:0.9rem;font-weight:600;cursor:pointer;">
            ✕ Reset
          </button>
        </div>
        <div id="searchStatus" style="display:none;font-size:0.84rem;color:var(--text-muted);margin-bottom:10px;"></div>
        <div id="searchLoading" style="display:none;text-align:center;padding:20px;color:var(--text-muted);">Mencari...</div>
        <div id="searchResults"></div>
      </div>
    </div>
  </div>
  @endauth
  @endif

  {{-- Fitur Cards --}}
  <div style="padding: 20px 20px 0px; max-width:1100px; margin: 0 auto;">
    <div class="beranda-fitur">
      <div class="fitur-card">
        <div class="fitur-icon">📚</div>
        <h3>Koleksi Buku</h3>
        <p>Lihat seluruh koleksi buku yang tersedia di perpustakaan UPA UNEJ.</p>
        <a href="{{ route('buku.index') }}" class="fitur-link">Lihat Koleksi</a>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon">📝</div>
        <h3>Peminjaman Buku</h3>
        <p>Ajukan peminjaman buku dengan mudah. Pilih buku, tentukan tanggal, dan selesai.</p>
        @auth
          <a href="{{ route('peminjaman.create') }}" class="fitur-link">Pinjam Sekarang</a>
        @else
          <a href="{{ route('login') }}" class="fitur-link">Login untuk Meminjam</a>
        @endauth
      </div>
      <div class="fitur-card">
        <div class="fitur-icon">🔄</div>
        <h3>Pengembalian Buku</h3>
        <p>Kembalikan buku tepat waktu. Sistem otomatis menghitung denda jika terlambat.</p>
        @auth
          <a href="{{ route('pengembalian.index') }}" class="fitur-link">Kelola Pengembalian</a>
        @else
          <a href="{{ route('login') }}" class="fitur-link">Login untuk Mengembalikan</a>
        @endauth
      </div>
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
// Live Search AJAX — kirim GET ke /peminjaman-search
let searchTimeout = null; //simpan timer

function liveSearch(keyword) {
  clearTimeout(searchTimeout); //hapus timer
  searchTimeout = setTimeout(() => doSearch(keyword), 400); // debounce 400ms
}

async function doSearch(keyword) { //komunikasi server
  const status  = document.getElementById('searchStatus');
  const loading = document.getElementById('searchLoading');
  const results = document.getElementById('searchResults');

  if (!keyword.trim()) { // validasi input kosong
    results.innerHTML = '';
    status.style.display = 'none';
    return;
  }

  loading.style.display = 'block';
  results.innerHTML = '';
  status.style.display = 'none';

  try { //coba jalankan request AJAX
    const response = await fetch(`/peminjaman-search?keyword=${encodeURIComponent(keyword)}`, { // req data tanpa refresh, kirim GET ke server
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json', // minta response JSON
      }
    });

    const json = await response.json(); //ubah jadi json
    loading.style.display = 'none';
    status.style.display  = 'block';
    status.textContent    = `Ditemukan ${json.total} hasil untuk "${json.keyword}"`;

    if (json.data.length === 0) {
      results.innerHTML = `<div style="text-align:center;padding:32px;color:var(--text-muted);">Tidak ada data yang cocok dengan "<strong>${keyword}</strong>"</div>`;
      return;
    }

    // Render tabel hasil — DOM manipulation
    const badgeStyle = { 'Dipinjam': 'background:#fff3cd;color:#856404;', 'Dikembalikan': 'background:#d4edda;color:#155724;', 'Terlambat': 'background:#f8d7da;color:#721c24;' };
    const rows = json.data.map((item, i) => `
      <tr style="border-bottom:1px solid var(--border);">
        <td style="padding:10px 14px;font-size:0.85rem;color:var(--text-muted);">${i+1}</td>
        <td style="padding:10px 14px;font-size:0.85rem;font-weight:600;color:var(--primary);">${item.id_anggota}</td>
        <td style="padding:10px 14px;font-size:0.85rem;">${item.nama_peminjam}</td>
        <td style="padding:10px 14px;font-size:0.85rem;">${item.judul_buku}</td>
        <td style="padding:10px 14px;">
          <span style="padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;${badgeStyle[item.status]||''}">${item.status}</span>
        </td>
        <td style="padding:10px 14px;">
          <a href="/peminjaman/${item.id}" style="font-size:0.8rem;color:var(--primary-light);font-weight:600;">Lihat</a>
        </td>
      </tr>`).join('');

    results.innerHTML = `
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:#f0f4f8;">
            <th style="padding:10px 14px;text-align:left;font-size:0.78rem;color:var(--text-muted);">NO</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.78rem;color:var(--text-muted);">ID ANGGOTA</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.78rem;color:var(--text-muted);">NAMA</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.78rem;color:var(--text-muted);">JUDUL BUKU</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.78rem;color:var(--text-muted);">STATUS</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.78rem;color:var(--text-muted);">AKSI</th>
          </tr>
        </thead>
        <tbody>${rows}</tbody>
      </table>`;

  } catch (err) {
    loading.style.display = 'none';
    results.innerHTML = `<p style="color:#c0392b;padding:16px;">Error: ${err.message}</p>`;
  }
}

function resetSearch() {
  document.getElementById('searchInput').value = '';
  document.getElementById('searchResults').innerHTML = '';
  document.getElementById('searchStatus').style.display = 'none';
}
</script>
@endpush
