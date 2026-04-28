@extends('layout')

@section('title', 'Tentang — Sistem Informasi Perpustakaan')

@section('content')

    <section id="tentang" class="beranda">

      <div class="beranda-hero" style="background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #2e86c1 100%);">
        <div class="beranda-hero-content">
          <div class="beranda-badge">ℹ️ Tentang Kami</div>
          <h2>Sistem Informasi<br/>Perpustakaan</h2>
          <p>Aplikasi web berbasis Laravel untuk membantu pengelolaan peminjaman dan pengembalian buku perpustakaan secara digital dan efisien.</p>
          <div class="beranda-cta">
            <a href="{{ route('beranda') }}" class="cta-btn cta-primary">🏠 Kembali ke Beranda</a>
            <a href="{{ route('peminjaman') }}" class="cta-btn cta-secondary">📝 Mulai Peminjaman</a>
          </div>
        </div>
        <div class="beranda-hero-art">
          <div class="hero-book-icon">📖</div>
        </div>
      </div>

      <!-- Info Cards -->
      <div class="beranda-fitur">
        <div class="fitur-card">
          <div class="fitur-icon">🎯</div>
          <h3>Tujuan Aplikasi</h3>
          <p>Mempermudah petugas perpustakaan dalam mencatat, mengelola, dan memantau seluruh aktivitas peminjaman dan pengembalian buku secara digital.</p>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">🛠️</div>
          <h3>Teknologi</h3>
          <p>Dibangun menggunakan <strong>Laravel</strong> (PHP Framework), Blade Template, CSS3, dan JavaScript vanilla. Data sementara disimpan di localStorage.</p>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">👩‍💻</div>
          <h3>Pengembang</h3>
          <p>Dikembangkan oleh <strong>Chelsea Brilliant Syah Putra</strong> — NIM 242410101079, Kelas A, sebagai tugas Pemrograman Website.</p>
        </div>
      </div>

      <!-- Fitur Unggulan -->
      <div style="background:#fff; border-radius:12px; border:1px solid #d5e0ea; box-shadow:0 2px 8px rgba(27,79,114,0.08); overflow:hidden; margin-top:16px;">
        <h2 style="font-family:'Lora',serif; font-size:1.05rem; color:#fff; padding:16px 22px; margin:0; background:linear-gradient(135deg,#1b4f72,#2e86c1); border-bottom:3px solid #2ecc71;">
          ✨ Fitur Unggulan
        </h2>
        <div style="padding:24px 22px; display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px;">
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:1.5rem;">📝</span>
            <div>
              <strong style="color:#1b4f72;">Form Peminjaman</strong>
              <p style="font-size:0.855rem;color:#5d7285;margin-top:4px;">Input data peminjaman lengkap dengan validasi otomatis.</p>
            </div>
          </div>
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:1.5rem;">📋</span>
            <div>
              <strong style="color:#1b4f72;">Daftar & Tabel</strong>
              <p style="font-size:0.855rem;color:#5d7285;margin-top:4px;">Tampilkan semua data dengan tabel responsif dan zebra striping.</p>
            </div>
          </div>
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:1.5rem;">🔍</span>
            <div>
              <strong style="color:#1b4f72;">Pencarian Real-time</strong>
              <p style="font-size:0.855rem;color:#5d7285;margin-top:4px;">Cari data langsung tanpa reload halaman.</p>
            </div>
          </div>
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:1.5rem;">🔧</span>
            <div>
              <strong style="color:#1b4f72;">Edit & Hapus</strong>
              <p style="font-size:0.855rem;color:#5d7285;margin-top:4px;">Kelola data dengan tombol aksi edit dan hapus.</p>
            </div>
          </div>
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:1.5rem;">📊</span>
            <div>
              <strong style="color:#1b4f72;">Statistik Otomatis</strong>
              <p style="font-size:0.855rem;color:#5d7285;margin-top:4px;">Statistik diperbarui secara otomatis setiap ada perubahan data.</p>
            </div>
          </div>
          <div style="display:flex;align-items:flex-start;gap:12px;">
            <span style="font-size:1.5rem;">📱</span>
            <div>
              <strong style="color:#1b4f72;">Responsif</strong>
              <p style="font-size:0.855rem;color:#5d7285;margin-top:4px;">Tampilan mobile-first yang nyaman di semua ukuran layar.</p>
            </div>
          </div>
        </div>
      </div>

    </section>

@endsection
