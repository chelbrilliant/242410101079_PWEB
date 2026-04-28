@extends('layout')

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

      <!-- Statistik Utama -->
      <div class="beranda-stats">
        <div class="bstat-card">
          <div class="bstat-icon">📖</div>
          <div class="bstat-num" id="bstat-total">5</div>
          <div class="bstat-label">Total Peminjaman</div>
        </div>
        <div class="bstat-card">
          <div class="bstat-icon">🔄</div>
          <div class="bstat-num" id="bstat-dipinjam">2</div>
          <div class="bstat-label">Sedang Dipinjam</div>
        </div>
        <div class="bstat-card">
          <div class="bstat-icon">✅</div>
          <div class="bstat-num" id="bstat-kembali">1</div>
          <div class="bstat-label">Dikembalikan</div>
        </div>
        <div class="bstat-card bstat-warn">
          <div class="bstat-icon">⚠️</div>
          <div class="bstat-num" id="bstat-terlambat">1</div>
          <div class="bstat-label">Terlambat</div>
        </div>
      </div>

      <!-- Menu Fitur Admin -->
      <div class="beranda-fitur">
        <div class="fitur-card">
          <div class="fitur-icon">📝</div>
          <h3>Manajemen Peminjaman</h3>
          <p>Tambah, edit, dan hapus data peminjaman buku. Semua perubahan langsung tersimpan.</p>
          <a href="{{ route('peminjaman') }}" class="fitur-link">Kelola Peminjaman →</a>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">📋</div>
          <h3>Daftar & Laporan</h3>
          <p>Lihat seluruh riwayat peminjaman, filter per kategori atau status, dan cari data spesifik.</p>
          <a href="{{ route('daftar') }}" class="fitur-link">Lihat Laporan →</a>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">ℹ️</div>
          <h3>Tentang Sistem</h3>
          <p>Informasi mengenai Sistem Informasi Perpustakaan ini — fitur, teknologi, dan pengembang.</p>
          <a href="{{ route('tentang') }}" class="fitur-link">Baca Selengkapnya →</a>
        </div>
      </div>

      <!-- Tabel Peminjaman Terakhir -->
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
              <!-- Di-render oleh script.js -->
            </tbody>
          </table>
        </div>
      </div>

    </section>

@endsection

@push('scripts')
<script>
// Render ringkasan tabel di dashboard
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
