@extends('layouts.app')
@section('title', 'Dashboard — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background:linear-gradient(135deg,#0f2e42 0%,#1b4f72 60%,#2e86c1 100%);min-height:140px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">
        {{ auth()->user()->isAdmin() ? 'Panel Admin' : 'Panel Customer' }}
      </div>
      <h2>Halo, {{ auth()->user()->name }}!</h2>
      <p>
        @if(auth()->user()->isAdmin())
          Selamat datang di panel admin. Kelola semua data perpustakaan di sini.
        @else
          Lihat riwayat peminjaman dan status pengembalian bukumu.
        @endif
      </p>
      <div class="beranda-cta">
        <a href="{{ route('peminjaman.create') }}" class="cta-btn cta-primary">Pinjam Buku</a>
        <a href="{{ route('pengembalian.index') }}" class="cta-btn cta-secondary">Pengembalian</a>
      </div>
    </div>
    <div class="beranda-hero-art">
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="beranda-stats" style="padding:20px 20px;max-width:1100px;margin:20px auto 28px;">
    @foreach($statistik as $s)
      <div class="bstat-card" style="border-left: 4px solid {{ $s['warna'] }};">
        <div class="bstat-icon">{{ $s['ikon'] }}</div>
        <div class="bstat-num" style="color:{{ $s['warna'] }};">{{ $s['nilai'] }}</div>
        <div class="bstat-label">{{ $s['judul'] }}</div>
      </div>
    @endforeach
  </div>

  {{-- Warning stok buku habis (admin) --}}
  @if(auth()->user()->isAdmin() && $bukuHabis > 0)
    <div style="padding:0 20px;max-width:1100px;margin:0 auto 20px;">
      <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:14px 20px;display:flex;align-items:center;gap:12px;">
        <div>
          <strong>{{ $bukuHabis }} buku</strong> memiliki stok habis atau ditandai tidak tersedia.
          <a href="{{ route('buku.index') }}" style="color:var(--primary-light);font-weight:600;margin-left:8px;">Kelola Buku</a>
        </div>
      </div>
    </div>
  @endif

  {{-- Tabel peminjaman terbaru --}}
  <div style="padding:0 20px 32px;max-width:1100px;margin:0 auto;">
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="color:white;margin:0;font-size:1rem;">
          {{ auth()->user()->isAdmin() ? '5 Peminjaman Terbaru' : 'Peminjaman Terbaru Kamu' }}
        </h3>
        <a href="{{ route('peminjaman.index') }}" style="background:rgba(255,255,255,0.2);color:white;padding:6px 14px;border-radius:6px;font-size:0.82rem;">
          Lihat Semua
        </a>
      </div>

      @if($peminjamanTerbaru->isEmpty())
        <div style="padding:40px;text-align:center;color:var(--text-muted);">
          Belum ada data peminjaman.
          <br><a href="{{ route('peminjaman.create') }}" style="color:var(--primary-light);font-weight:600;">Buat peminjaman pertama</a>
        </div>
      @else
        <div style="overflow-x:auto;">
          <table style="width:100%;border-collapse:collapse;">
            <thead>
              <tr style="background:#f0f4f8;">
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">ID</th>
                @if(auth()->user()->isAdmin())
                  <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">PEMINJAM</th>
                @endif
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">JUDUL BUKU</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">TGL KEMBALI</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">STATUS</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">DENDA</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">AKSI</th>
              </tr>
            </thead>
            <tbody>
              @foreach($peminjamanTerbaru as $p)
                @php
                  $badgeStyle = match($p->status) {
                    'Dipinjam'     => 'background:#fff3cd;color:#856404;',
                    'Dikembalikan' => 'background:#d4edda;color:#155724;',
                    'Terlambat'    => 'background:#f8d7da;color:#721c24;',
                    default        => 'background:#e2e3e5;color:#383d41;',
                  };
                  $denda = $p->hitungDenda();
                @endphp
                <tr style="border-bottom:1px solid var(--border);">
                  <td style="padding:12px 16px;font-size:0.85rem;color:var(--text-muted);">#{{ $p->id }}</td>
                  @if(auth()->user()->isAdmin())
                    <td style="padding:12px 16px;font-size:0.85rem;font-weight:600;">{{ $p->nama_peminjam }}</td>
                  @endif
                  <td style="padding:12px 16px;font-size:0.85rem;">{{ $p->judul_buku }}</td>
                  <td style="padding:12px 16px;font-size:0.85rem;">
                    {{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}
                    @if($p->isTerlambat())
                      <span style="font-size:0.72rem;color:var(--danger);font-weight:600;display:block;">{{ $p->hitungKeterlambatan() }} hari terlambat</span>
                    @endif
                  </td>
                  <td style="padding:12px 16px;">
                    <span style="padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;{{ $badgeStyle }}">{{ $p->status }}</span>
                  </td>
                  <td style="padding:12px 16px;font-size:0.85rem;">
                    @if($denda > 0)
                      <span style="color:var(--danger);font-weight:600;">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                    @else
                      <span style="color:var(--text-muted);">-</span>
                    @endif
                  </td>
                  <td style="padding:12px 16px;">
                    <a href="{{ route('peminjaman.show', $p) }}" style="font-size:0.8rem;color:var(--primary-light);font-weight:600;">Detail</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  {{-- Shortcut admin --}}
  @if(auth()->user()->isAdmin())
  <div style="padding:0 20px 32px;max-width:1100px;margin:0 auto;">
    <div class="beranda-fitur">
      <div class="fitur-card">
        <div class="fitur-icon">📚</div>
        <h3>Kelola Buku</h3>
        <p>Tambah, edit, dan hapus koleksi buku perpustakaan.</p>
        <a href="{{ route('buku.index') }}" class="fitur-link">Kelola Buku</a>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon">📋</div>
        <h3>Semua Peminjaman</h3>
        <p>Lihat dan kelola semua data peminjaman dari seluruh customer.</p>
        <a href="{{ route('peminjaman.index') }}" class="fitur-link">Lihat Semua</a>
      </div>
      <div class="fitur-card">
        <div class="fitur-icon">🔄</div>
        <h3>Kelola Pengembalian</h3>
        <p>Proses pengembalian buku dan tandai denda yang sudah dibayar.</p>
        <a href="{{ route('pengembalian.index') }}" class="fitur-link">Kelola Pengembalian</a>
      </div>
    </div>
  </div>
  @endif

</section>
@endsection
