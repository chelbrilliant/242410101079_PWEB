@extends('layout')

@section('title', 'Daftar Buku — Sistem Informasi Perpustakaan')

@section('content')

    <main>

      <!-- Search Input -->
      <section id="pencarian">
        <h2>Cari Data Peminjaman</h2>
        <div style="padding:18px 22px;">
          <input type="text" id="search-input" placeholder="Cari nama, judul, ID anggota, kode buku..." style="width:100%;max-width:100%;" />
        </div>
      </section>

      <!-- Daftar Peminjaman -->
      <section id="daftar-buku">
        <h2>Daftar 5 Peminjaman Terakhir</h2>
        <table border="1">
          <thead>
            <tr>
              <th>No</th>
              <th>ID Anggota</th>
              <th>Nama Peminjam</th>
              <th>Judul Buku</th>
              <th>Kategori</th>
              <th>Tgl Pinjam</th>
              <th>Tgl Kembali</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <!-- Di-render oleh script.js -->
          </tbody>
        </table>
      </section>

    </main>

    <div class="sidebar-wrap">

      <aside id="statistik">
        <h3>Statistik Perpustakaan</h3>
        <p id="stat-total">Total Peminjaman: 5</p>
        <p id="stat-dipinjam">Buku Dipinjam: 2</p>
        <p id="stat-tersedia">Dikembalikan: 1</p>
        <p>Anggota Aktif: 342</p>
        <p id="stat-terlambat">Peminjaman Terlambat: 1</p>
      </aside>

      <div class="sidebar-filter filter-kategori">
        <h4>Filter Kategori</h4>
        <div class="checkbox-group">
          <label class="checkbox-label">
            <input type="checkbox" value="Fiksi" /> Fiksi
            <span class="checkbox-count">380</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" value="Non-Fiksi" /> Non-Fiksi
            <span class="checkbox-count">320</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" value="Ilmu Pengetahuan" /> Ilmu Pengetahuan
            <span class="checkbox-count">195</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" value="Sejarah" /> Sejarah
            <span class="checkbox-count">145</span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" value="Teknologi" /> Teknologi
            <span class="checkbox-count">210</span>
          </label>
        </div>
      </div>

      <div class="sidebar-filter filter-status">
        <h4>Filter Status Buku</h4>
        <div class="checkbox-group">
          <label class="checkbox-label">
            <input type="checkbox" value="Dikembalikan" /> Dikembalikan
            <span class="checkbox-count"></span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" value="Dipinjam" /> Dipinjam
            <span class="checkbox-count"></span>
          </label>
          <label class="checkbox-label">
            <input type="checkbox" value="Terlambat" /> Terlambat
            <span class="checkbox-count"></span>
          </label>
        </div>
      </div>

    </div>

    <div class="clearfix"></div>

@endsection
