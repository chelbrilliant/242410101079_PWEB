@extends('layouts.app')

@section('title', 'Dashboard — Sistem Informasi Perpustakaan')

@section('content')

    <section id="dashboard" class="beranda">

      <div class="beranda-hero" style="background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #117a65 100%);">
        <div class="beranda-hero-content">
          <div class="beranda-badge">⚙️ Panel Admin</div>
          <h2>Dashboard<br/>Perpustakaan</h2>
          <p>Pantau seluruh aktivitas perpustakaan secara real-time. Kelola data peminjaman, lihat statistik, dan monitor status buku.</p>
          <div class="beranda-cta">
            <a href="{{ route('peminjaman') }}" class="cta-btn cta-primary">📝 Tambah Peminjaman</a>
            <a href="{{ route('daftar') }}" class="cta-btn cta-secondary">📋 Lihat Semua Data</a>
          </div>
        </div>
        <div class="beranda-hero-art">
          <div class="hero-book-icon">⚙️</div>
        </div>
      </div>

      {{-- Statistik menggunakan komponen x-stat-card - soal no.5 --}}
      <div class="beranda-stats">
        <x-stat-card judul="Total Peminjaman" :nilai="$statistik[0]['nilai']" ikon="📖" />
        <x-stat-card judul="Sedang Dipinjam"  :nilai="$statistik[1]['nilai']" ikon="🔄" />
        <x-stat-card judul="Dikembalikan"     :nilai="$statistik[2]['nilai']" ikon="✅" warna="success" />
        <x-stat-card judul="Terlambat"        :nilai="$statistik[3]['nilai']" ikon="⚠️" warna="warning" />
      </div>

      {{-- Menu Fitur Admin dengan @forelse - soal no.6 --}}
      <div class="beranda-fitur">
        @forelse($menuAdmin as $menu)
          <div class="fitur-card">
            <div class="fitur-icon">{{ $menu['ikon'] }}</div>
            <h3>{{ $menu['judul'] }}</h3>
            <p>{{ $menu['deskripsi'] }}</p>
            <a href="{{ route($menu['route']) }}" class="fitur-link">{{ $menu['label'] }} →</a>
          </div>
        @empty
          <p>Menu tidak tersedia.</p>
        @endforelse
      </div>

      {{-- Tabel Peminjaman Terakhir --}}
      <div style="background:#fff; border-radius:12px; border:1px solid #d5e0ea; box-shadow:0 2px 8px rgba(27,79,114,0.08); overflow:hidden; margin-top:16px;">
        <h2 style="font-family:'Lora',serif; font-size:1.05rem; color:#fff; padding:16px 22px; margin:0; background:linear-gradient(135deg, #1b4f72 0%, #2e86c1 100%); border-bottom:3px solid #2ecc71;">
          📊 Data Peminjaman Terkini
        </h2>
        <div style="overflow-x:auto;">
          <table border="1" style="width:100%;border-collapse:collapse;font-size:0.84rem;">
            <thead>
              <tr style="background:linear-gradient(135deg,#1b4f72,#2e86c1);">
                <th style="color:#fff;padding:12px 14px;text-align:left;font-size:0.75rem;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;white-space:nowrap;border:none;">No</th>
                <th style="color:#fff;padding:12px 14px;text-align:left;font-size:0.75rem;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;white-space:nowrap;border:none;">ID Anggota</th>
                <th style="color:#fff;padding:12px 14px;text-align:left;font-size:0.75rem;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;white-space:nowrap;border:none;">Nama Peminjam</th>
                <th style="color:#fff;padding:12px 14px;text-align:left;font-size:0.75rem;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;white-space:nowrap;border:none;">Judul Buku</th>
                <th style="color:#fff;padding:12px 14px;text-align:left;font-size:0.75rem;font-weight:600;letter-spacing:0.07em;text-transform:uppercase;white-space:nowrap;border:none;">Status</th>
              </tr>
            </thead>
            <tbody id="dashboard-tabel">
              {{-- Di-render oleh script.js --}}
            </tbody>
          </table>
        </div>
      </div>

    </section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const tbody = document.getElementById('dashboard-tabel');
  if (!tbody) return;

  const data = JSON.parse(localStorage.getItem('perpustakaan_data')) || [];
  const statusBadge = (status) => {
    const map = { 'Dikembalikan': 'badge-success', 'Dipinjam': 'badge-warning', 'Terlambat': 'badge-danger' };
    return `<span class="badge ${map[status] || ''}">${status}</span>`;
  };

  if (data.length === 0) {
    tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:24px;color:#5d7285;">Belum ada data peminjaman.</td></tr>`;
    return;
  }

  tbody.innerHTML = data.slice(-5).reverse().map((d, i) => `
    <tr style="background:${i % 2 === 0 ? '#ffffff' : '#f3f8fd'};border-bottom:1px solid #d5e0ea;">
      <td style="padding:11px 14px;">${i + 1}</td>
      <td style="padding:11px 14px;">${d.idAnggota}</td>
      <td style="padding:11px 14px;">${d.nama}</td>
      <td style="padding:11px 14px;">${d.judul}</td>
      <td style="padding:11px 14px;">${statusBadge(d.status)}</td>
    </tr>
  `).join('');
});
</script>
@endpush
