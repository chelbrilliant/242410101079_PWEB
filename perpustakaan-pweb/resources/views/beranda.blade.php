@extends('layouts.app')

@section('title', 'Beranda — Sistem Informasi Perpustakaan')

@section('content')
<section class="beranda">

  {{-- Hero --}}
  <div class="beranda-hero" style="background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #2e86c1 100%);">
    <div class="beranda-hero-content">
      <div class="beranda-badge">📚 Sistem Informasi Perpustakaan</div>
      <h2>Selamat Datang di<br/>Perpustakaan Digital</h2>
      <p>Kelola peminjaman dan pengembalian buku dengan mudah, cepat, dan efisien.</p>
      <div class="beranda-cta">
        @auth
          <a href="{{ route('peminjaman.index') }}" class="cta-btn cta-primary">📝 Form Peminjaman</a>
        @else
          <a href="{{ route('login') }}" class="cta-btn cta-primary">🔐 Login Dulu</a>
        @endauth
        <a href="{{ route('daftar') }}" class="cta-btn cta-secondary">📋 Lihat Daftar</a>
      </div>
    </div>
    <div class="beranda-hero-art">
      <div class="hero-book-icon">📚</div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════════════ --}}
  {{-- SOAL 1 — Section Cuaca Surabaya (async/await + API) --}}
  {{-- ═══════════════════════════════════════════════════════ --}}
  <div style="padding: 32px; max-width: 1100px; margin: 0 auto;">
    <h2 style="font-family: var(--font-display); font-size: 1.3rem; margin-bottom: 20px;">
      🌤 Informasi Cuaca Kota
    </h2>

    <div id="cuaca-container"
         style="background: linear-gradient(135deg, #1b4f72, #2e86c1); border-radius: var(--radius); padding: 28px; color: white; min-height: 120px; display:flex; align-items:center; justify-content:center;">

      {{-- Loading indicator --}}
      <div id="cuaca-loading" style="text-align:center;">
        <div style="font-size: 2rem; animation: spin 1s linear infinite; display:inline-block;">⏳</div>
        <p style="margin-top:10px; opacity:0.8; font-size:0.9rem;">Mengambil data cuaca...</p>
      </div>

      {{-- Data cuaca (tersembunyi dulu) --}}
      <div id="cuaca-data" style="display:none; width:100%;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; text-align:center;">
          <div>
            <p style="font-size:0.8rem; opacity:0.75; margin:0; text-transform:uppercase; letter-spacing:1px;">Kota</p>
            <p id="cuaca-kota" style="font-size:1.4rem; font-weight:700; margin:6px 0 0;">—</p>
          </div>
          <div>
            <p style="font-size:0.8rem; opacity:0.75; margin:0; text-transform:uppercase; letter-spacing:1px;">Suhu Saat Ini</p>
            <p id="cuaca-suhu" style="font-size:2.2rem; font-weight:700; margin:6px 0 0; color:#2ecc71;">—</p>
          </div>
          <div>
            <p style="font-size:0.8rem; opacity:0.75; margin:0; text-transform:uppercase; letter-spacing:1px;">Kondisi</p>
            <p id="cuaca-deskripsi" style="font-size:1rem; font-weight:600; margin:6px 0 0;">—</p>
          </div>
        </div>
        <div style="margin-top:16px; padding-top:16px; border-top:1px solid rgba(255,255,255,0.2); display:flex; gap:24px; justify-content:center; font-size:0.85rem; opacity:0.8;">
          <span>💧 Kelembapan: <span id="cuaca-humidity">—</span></span>
          <span>💨 Angin: <span id="cuaca-wind">—</span> km/j</span>
          <span>🕐 Update: <span id="cuaca-time">—</span></span>
        </div>
      </div>

      {{-- Error --}}
      <div id="cuaca-error" style="display:none; text-align:center;">
        <p style="font-size:1.5rem;">😕</p>
        <p id="cuaca-error-msg" style="opacity:0.8;">Gagal mengambil data cuaca.</p>
        <button onclick="fetchCuaca()" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.4);padding:8px 18px;border-radius:8px;cursor:pointer;margin-top:8px;">
          🔄 Coba Lagi
        </button>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════════════════════════ --}}
  {{-- SOAL 2 — Live Search Peminjaman via AJAX             --}}
  {{-- ═══════════════════════════════════════════════════════ --}}
  @auth
  <div style="padding: 0 32px 32px; max-width: 1100px; margin: 0 auto;">
    <h2 style="font-family: var(--font-display); font-size: 1.3rem; margin-bottom: 20px;">
      🔍 Live Search Peminjaman
    </h2>

    <div style="background:white; border-radius:var(--radius); box-shadow:var(--shadow-md); overflow:hidden;">
      <div style="background: linear-gradient(135deg, var(--primary-dark), var(--primary)); padding:16px 24px;">
        <h3 style="color:white; margin:0; font-size:1rem;">Cari Data Peminjaman (AJAX — tanpa reload)</h3>
      </div>

      <div style="padding: 24px;">
        {{-- Form Search — dikirim via AJAX, bukan submit biasa --}}
        <div style="display:flex; gap:12px; margin-bottom:20px;">
          <input type="text"
                 id="searchInput"
                 placeholder="Cari nama peminjam, judul buku, atau ID anggota..."
                 style="flex:1; padding:11px 16px; border:1.5px solid var(--border); border-radius:8px; font-size:0.92rem; font-family:var(--font-body); outline:none;"
                 oninput="liveSearch(this.value)" />
          <button onclick="liveSearch(document.getElementById('searchInput').value)"
                  style="background:var(--primary); color:white; padding:11px 22px; border:none; border-radius:8px; font-size:0.92rem; font-weight:600; cursor:pointer;">
            🔍 Cari
          </button>
          <button onclick="resetSearch()"
                  style="background:#f0f4f8; color:var(--text); padding:11px 18px; border:none; border-radius:8px; font-size:0.92rem; font-weight:600; cursor:pointer;">
            ✕ Reset
          </button>
        </div>

        {{-- Status pencarian --}}
        <div id="searchStatus" style="display:none; font-size:0.85rem; color:var(--text-muted); margin-bottom:12px;"></div>

        {{-- Loading AJAX --}}
        <div id="searchLoading" style="display:none; text-align:center; padding:20px; color:var(--text-muted);">
          ⏳ Mencari...
        </div>

        {{-- Hasil pencarian --}}
        <div id="searchResults"></div>
      </div>
    </div>
  </div>
  @endauth

  {{-- Stats --}}
  <div class="beranda-stats" style="padding: 0 32px 32px; max-width:1100px; margin:0 auto;">
    <div class="bstat-card">
      <div class="bstat-icon">📖</div>
      <div class="bstat-num" id="stat-total">—</div>
      <div class="bstat-label">Total Peminjaman</div>
    </div>
    <div class="bstat-card">
      <div class="bstat-icon">🔄</div>
      <div class="bstat-num" id="stat-dipinjam">—</div>
      <div class="bstat-label">Sedang Dipinjam</div>
    </div>
    <div class="bstat-card">
      <div class="bstat-icon">✅</div>
      <div class="bstat-num" id="stat-kembali">—</div>
      <div class="bstat-label">Dikembalikan</div>
    </div>
    <div class="bstat-card">
      <div class="bstat-icon">⚠️</div>
      <div class="bstat-num" id="stat-terlambat">—</div>
      <div class="bstat-label">Terlambat</div>
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
// ═══════════════════════════════════════════════
// SOAL 1 — Fetch cuaca Surabaya async/await
// ═══════════════════════════════════════════════
async function fetchCuaca() {
  const loading = document.getElementById('cuaca-loading');
  const dataEl  = document.getElementById('cuaca-data');
  const errorEl = document.getElementById('cuaca-error');

  // Tampilkan loading indicator
  loading.style.display = 'block';
  dataEl.style.display  = 'none';
  errorEl.style.display = 'none';

  try {
    // Fetch API cuaca wttr.in — async/await
    const response = await fetch('https://wttr.in/Surabaya?format=j1');

    if (!response.ok) throw new Error('HTTP error: ' + response.status);

    const json = await response.json();

    // Ambil data yang dibutuhkan
    const current     = json.current_condition[0];
    const suhu        = current.temp_C;
    const deskripsi   = current.weatherDesc[0].value;
    const humidity    = current.humidity;
    const wind        = current.windspeedKmph;
    const kota        = json.nearest_area[0].areaName[0].value;
    const waktu       = new Date().toLocaleTimeString('id-ID');

    // Tampilkan ke DOM
    document.getElementById('cuaca-kota').textContent        = kota;
    document.getElementById('cuaca-suhu').textContent        = suhu + ' °C';
    document.getElementById('cuaca-deskripsi').textContent   = deskripsi;
    document.getElementById('cuaca-humidity').textContent    = humidity + '%';
    document.getElementById('cuaca-wind').textContent        = wind;
    document.getElementById('cuaca-time').textContent        = waktu;

    loading.style.display = 'none';
    dataEl.style.display  = 'block';

  } catch (err) {
    console.error('Cuaca error:', err);
    loading.style.display = 'none';
    errorEl.style.display = 'block';
    document.getElementById('cuaca-error-msg').textContent =
      'Gagal mengambil data cuaca: ' + err.message;
  }
}

// ═══════════════════════════════════════════════
// SOAL 2 — Live Search via AJAX GET
// ═══════════════════════════════════════════════
let searchTimeout = null;

function liveSearch(keyword) {
  // Debounce — tunggu 400ms setelah user berhenti mengetik
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => doSearch(keyword), 400);
}

async function doSearch(keyword) {
  const status  = document.getElementById('searchStatus');
  const loading = document.getElementById('searchLoading');
  const results = document.getElementById('searchResults');

  if (keyword.trim() === '') {
    results.innerHTML = '';
    status.style.display = 'none';
    return;
  }

  loading.style.display = 'block';
  results.innerHTML = '';
  status.style.display = 'none';

  try {
    // AJAX GET ke Laravel — CSRF via header
    const response = await fetch(`/peminjaman-search?keyword=${encodeURIComponent(keyword)}`, {
      method: 'GET',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      }
    });

    const json = await response.json();
    loading.style.display = 'none';

    // Tampilkan status
    status.style.display = 'block';
    status.textContent   = `Ditemukan ${json.total} hasil untuk "${json.keyword}"`;

    // Render hasil — langsung muncul tanpa reload
    if (json.data.length === 0) {
      results.innerHTML = `
        <div style="text-align:center; padding:32px; color:var(--text-muted);">
          📭 Tidak ada data yang cocok dengan "<strong>${keyword}</strong>"
        </div>`;
      return;
    }

    const rows = json.data.map((item, i) => {
      const badge = {
        'Dipinjam':     'background:#fff3cd;color:#856404;',
        'Dikembalikan': 'background:#d4edda;color:#155724;',
        'Terlambat':    'background:#f8d7da;color:#721c24;',
      }[item.status] || '';

      const avatar = item.foto
        ? `<img src="/storage/${item.foto}" style="width:32px;height:32px;border-radius:50%;object-fit:cover;" />`
        : `<div style="width:32px;height:32px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;">
             ${item.nama_peminjam.charAt(0).toUpperCase()}
           </div>`;

      return `
        <tr style="border-bottom:1px solid var(--border);">
          <td style="padding:10px 14px;font-size:0.85rem;color:var(--text-muted);">${i+1}</td>
          <td style="padding:10px 14px;font-size:0.85rem;font-weight:600;color:var(--primary);">${item.id_anggota}</td>
          <td style="padding:10px 14px;font-size:0.85rem;">
            <div style="display:flex;align-items:center;gap:8px;">${avatar} ${item.nama_peminjam}</div>
          </td>
          <td style="padding:10px 14px;font-size:0.85rem;">${item.judul_buku}</td>
          <td style="padding:10px 14px;">
            <span style="padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;${badge}">${item.status}</span>
          </td>
        </tr>`;
    }).join('');

    results.innerHTML = `
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="background:#f0f4f8;">
            <th style="padding:10px 14px;text-align:left;font-size:0.8rem;color:var(--text-muted);">NO</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.8rem;color:var(--text-muted);">ID</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.8rem;color:var(--text-muted);">NAMA</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.8rem;color:var(--text-muted);">JUDUL BUKU</th>
            <th style="padding:10px 14px;text-align:left;font-size:0.8rem;color:var(--text-muted);">STATUS</th>
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

// Jalankan fetch cuaca saat halaman load
document.addEventListener('DOMContentLoaded', () => {
  fetchCuaca();
  console.log('Halaman Beranda loaded');
});
</script>
@endpush
