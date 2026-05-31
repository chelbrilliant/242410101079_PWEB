@extends('layouts.app')
@section('title', 'Tentang — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  <div class="beranda-hero">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Tentang Sistem</div>
      <h2>Sistem Informasi<br/>Perpustakaan UPA UNEJ</h2>
      <p>Sistem pengelolaan peminjaman dan pengembalian buku perpustakaan Unit Pelaksana Akademik Universitas Jember.</p>
      <div class="beranda-cta">
        <a href="{{ route('buku.index') }}" class="cta-btn cta-primary">Lihat Koleksi Buku</a>
        @auth
          <a href="{{ route('peminjaman.create') }}" class="cta-btn cta-secondary">Pinjam Buku</a>
        @else
          <a href="{{ route('login') }}" class="cta-btn cta-secondary">Login</a>
        @endauth
      </div>
    </div>
  </div>

  {{-- Tentang sistem --}}
  <div style="padding:32px 20px;max-width:1000px;margin:0 auto;">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px;">

      <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
        <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:16px 24px;">
          <h3 style="color:white;margin:0;font-size:1rem;">Tentang Perpustakaan</h3>
        </div>
        <div style="padding:24px;">
          <p style="color:var(--text);font-size:0.9rem;line-height:1.7;margin:0 0 12px;">
            PustakaUPA adalah perpustakaan pusat yang melayani seluruh civitas akademika Universitas Jember.
          </p>
          <p style="color:var(--text);font-size:0.9rem;line-height:1.7;margin:0;">
            Perpustakaan menyediakan koleksi buku, jurnal, dan referensi ilmiah yang mendukung kegiatan akademik, penelitian, dan pengabdian masyarakat.
          </p>
        </div>
      </div>

      <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
        <div style="background:linear-gradient(135deg,#145a32,#1e8449);padding:16px 24px;">
          <h3 style="color:white;margin:0;font-size:1rem;">Tentang Sistem</h3>
        </div>
        <div style="padding:24px;">
          <p style="color:var(--text);font-size:0.9rem;line-height:1.7;margin:0 0 12px;">
            Sistem Informasi Perpustakaan ini dibangun untuk memudahkan pengelolaan peminjaman dan pengembalian buku secara digital, menggantikan pencatatan manual.
          </p>
        </div>
      </div>

    </div>

    {{-- Fitur sistem --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:32px;">
      <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:16px 28px;">
        <h3 style="color:white;margin:0;">Fitur Sistem</h3>
      </div>
      <div style="padding:28px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
          @php
            $fitur = [
              ['judul' => 'Autentikasi Pengguna',      'desc' => 'Login dan registrasi dengan dua role: Admin dan Customer. Admin dibuat oleh sistem, Customer bisa mendaftar sendiri.'],
              ['judul' => 'Kelola Koleksi Buku',        'desc' => 'Admin dapat menambah, mengedit, dan menghapus data buku. Stok otomatis berkurang saat dipinjam dan bertambah saat dikembalikan.'],
              ['judul' => 'Peminjaman Buku',            'desc' => 'Customer dapat mengajukan peminjaman buku dengan memilih dari daftar buku yang tersedia secara real-time via AJAX.'],
              ['judul' => 'Pengembalian & Denda',       'desc' => 'Sistem otomatis menghitung denda keterlambatan (Rp 1.000/hari). Denda dicatat di database dan dibayar manual ke petugas.'],
              ['judul' => 'Live Search AJAX',           'desc' => 'Pencarian data peminjaman secara real-time tanpa reload halaman menggunakan Fetch API dan JSON response.'],
              ['judul' => 'Cookie & Session',           'desc' => 'Nama pengguna disimpan di cookie 30 hari untuk fitur "Selamat datang kembali". Session digunakan untuk proteksi autentikasi.'],
            ];
          @endphp
          @foreach($fitur as $f)
            <div style="display:flex;gap:14px;padding:16px;background:#f8fbff;border-radius:10px;">

              <div>
                <h4 style="font-size:0.9rem;font-weight:700;margin:0 0 6px;color:var(--primary);">{{ $f['judul'] }}</h4>
                <p style="font-size:0.82rem;color:var(--text-muted);line-height:1.6;margin:0;">{{ $f['desc'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Alur penggunaan --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:32px;">
      <div style="background:linear-gradient(135deg,#4a235a,#7d3c98);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Alur Penggunaan Sistem</h3>
      </div>
      <div style="padding:28px;">
        <div style="display:flex;flex-direction:column;gap:0;">
          @php
            $alur = [
              ['no' => '1', 'judul' => 'Daftar / Login',         'desc' => 'Customer mendaftar akun baru atau login. Admin menggunakan akun bawaan sistem.'],
              ['no' => '2', 'judul' => 'Pilih & Pinjam Buku',    'desc' => 'Customer memilih buku yang tersedia, menentukan tanggal pinjam dan tanggal kembali, lalu submit.'],
              ['no' => '3', 'judul' => 'Ambil Buku di Perpustakaan', 'desc' => 'Customer datang ke perpustakaan, tunjukkan bukti peminjaman dari sistem kepada petugas, dan ambil buku fisiknya.'],
              ['no' => '4', 'judul' => 'Kembalikan Buku',        'desc' => 'Setelah selesai, kembalikan buku fisik ke perpustakaan. Petugas/customer update status di sistem jadi "Dikembalikan".'],
              ['no' => '5', 'judul' => 'Bayar Denda (jika ada)', 'desc' => 'Jika terlambat, sistem menghitung denda otomatis. Bayar langsung ke petugas perpustakaan. Admin menandai denda lunas di sistem.'],
            ];
          @endphp
          @foreach($alur as $i => $a)
            <div style="display:flex;gap:16px;{{ $i < count($alur)-1 ? 'margin-bottom:0;' : '' }}">
              <div style="display:flex;flex-direction:column;align-items:center;">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:0.9rem;flex-shrink:0;">
                  {{ $a['no'] }}
                </div>
                @if($i < count($alur)-1)
                  <div style="width:2px;background:var(--border);flex:1;min-height:32px;margin:4px 0;"></div>
                @endif
              </div>
              <div style="padding-bottom:{{ $i < count($alur)-1 ? '20px' : '0' }};">
                <h4 style="font-size:0.92rem;font-weight:700;margin:4px 0 4px;color:var(--text);">{{ $a['judul'] }}</h4>
                <p style="font-size:0.84rem;color:var(--text-muted);line-height:1.6;margin:0;">{{ $a['desc'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

        </div>
      </div>
    </div>

  </div>
</section>
@endsection
