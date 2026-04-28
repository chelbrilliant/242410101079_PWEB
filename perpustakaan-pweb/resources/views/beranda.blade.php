@extends('layout')

@section('title', 'Beranda — Sistem Informasi Perpustakaan')

@section('content')

    <section id="beranda" class="beranda">
      <div class="beranda-hero">
        <div class="beranda-hero-content">
          <div class="beranda-badge">📚 Sistem Informasi Perpustakaan</div>
          <h2>Selamat Datang di<br/>Perpustakaan Digital</h2>
          <p>Kelola peminjaman dan pengembalian buku dengan mudah, cepat, dan efisien. Semua data tercatat rapi dalam satu sistem.</p>
          <div class="beranda-cta">
            <a href="{{ route('peminjaman') }}" class="cta-btn cta-primary">📝 Form Peminjaman</a>
            <a href="{{ route('daftar') }}" class="cta-btn cta-secondary">📋 Lihat Daftar</a>
          </div>
        </div>
        <div class="beranda-hero-art">
          <div class="hero-book-icon">📚</div>
        </div>
      </div>

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

      <div class="beranda-fitur">
        <div class="fitur-card">
          <div class="fitur-icon">📝</div>
          <h3>Form Peminjaman</h3>
          <p>Catat peminjaman buku dengan lengkap — ID anggota, judul, tanggal, dan petugas.</p>
          <a href="{{ route('peminjaman') }}" class="fitur-link">Buka Form →</a>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">📋</div>
          <h3>Daftar Peminjaman</h3>
          <p>Lihat seluruh riwayat peminjaman, edit data, atau hapus catatan yang tidak diperlukan.</p>
          <a href="{{ route('daftar') }}" class="fitur-link">Lihat Daftar →</a>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">🔍</div>
          <h3>Pencarian & Filter</h3>
          <p>Cari data peminjaman berdasarkan nama, judul, kode buku, atau filter per kategori.</p>
          <a href="{{ route('daftar') }}" class="fitur-link">Cari Data →</a>
        </div>
      </div>
    </section>

@endsection
