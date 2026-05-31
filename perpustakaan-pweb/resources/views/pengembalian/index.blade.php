@extends('layouts.app')
@section('title', 'Pengembalian — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background:linear-gradient(135deg,#0b3d2e,#1e8449);min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Pengembalian Buku</div>
      <h2>{{ auth()->user()->isAdmin() ? 'Kelola Pengembalian' : 'Pengembalian Kamu' }}</h2>
      <p>
        {{ auth()->user()->isAdmin()
          ? 'Daftar semua peminjaman yang belum dikembalikan. Proses pengembalian dan tandai denda yang sudah dibayar.'
          : 'Daftar buku yang sedang kamu pinjam. Klik "Kembalikan" untuk memproses pengembalian.' }}
      </p>
    </div>
  </div>

  <div style="padding:24px 20px;max-width:1100px;margin:0 auto;">

    @if($peminjaman->isEmpty())
      <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:48px;text-align:center;">
        <div style="font-size:3rem;margin-bottom:16px;"></div>
        <h3 style="font-family:var(--font-display);color:var(--text);margin:0 0 8px;">Semua buku sudah dikembalikan!</h3>
        <p style="color:var(--text-muted);margin:0 0 20px;">Tidak ada peminjaman aktif saat ini.</p>
        <a href="{{ route('peminjaman.create') }}"
           style="background:linear-gradient(135deg,var(--primary),var(--primary-light));color:white;padding:11px 24px;border-radius:8px;font-size:0.9rem;font-weight:600;">
          Pinjam Buku Baru
        </a>
      </div>

    @else
      <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
        <div style="background:linear-gradient(135deg,#0b3d2e,#1e8449);padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
          <h3 style="color:white;margin:0;">Daftar Pengembalian ({{ $peminjaman->total() }})</h3>
        </div>

        <div style="overflow-x:auto;">
          <table style="width:100%;border-collapse:collapse;">
            <thead>
              <tr style="background:#f0f4f8;">
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">NO</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">ID</th>
                @if(auth()->user()->isAdmin())
                  <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">PEMINJAM</th>
                @endif
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">JUDUL BUKU</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">TGL KEMBALI</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">STATUS</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">ESTIMASI DENDA</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">AKSI</th>
              </tr>
            </thead>
            <tbody>
              @foreach($peminjaman as $i => $p)
                @php
                  $keterlambatan = $p->hitungKeterlambatan();
                  $denda         = $p->hitungDenda();
                  $isTerlambat   = $keterlambatan > 0;
                @endphp
                <tr style="border-bottom:1px solid var(--border);{{ $isTerlambat ? 'background:#fff8f8;' : '' }}">
                  <td style="padding:12px 14px;font-size:0.84rem;color:var(--text-muted);">{{ $peminjaman->firstItem() + $i }}</td>
                  <td style="padding:12px 14px;font-size:0.82rem;font-weight:600;color:var(--primary);">{{ $p->id_anggota }}</td>
                  @if(auth()->user()->isAdmin())
                    <td style="padding:12px 14px;font-size:0.85rem;font-weight:600;">{{ $p->nama_peminjam }}</td>
                  @endif
                  <td style="padding:12px 14px;font-size:0.85rem;">{{ $p->judul_buku }}</td>
                  <td style="padding:12px 14px;font-size:0.84rem;">
                    {{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}
                    @if($isTerlambat)
                      <span style="display:block;font-size:0.72rem;color:var(--danger);font-weight:700;">{{ $keterlambatan }} hari terlambat!</span>
                    @else
                      @php $sisaHari = \Carbon\Carbon::today()->diffInDays($p->tanggal_kembali, false); @endphp
                      @if($sisaHari >= 0)
                        <span style="display:block;font-size:0.72rem;color:var(--success);font-weight:600;">Sisa {{ $sisaHari }} hari</span>
                      @endif
                    @endif
                  </td>
                  <td style="padding:12px 14px;">
                    @if($isTerlambat)
                      <span style="background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:700;">Terlambat</span>
                    @else
                      <span style="background:#fff3cd;color:#856404;padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;">Dipinjam</span>
                    @endif
                  </td>
                  <td style="padding:12px 14px;font-size:0.84rem;">
                    @if($denda > 0)
                      <span style="color:var(--danger);font-weight:700;">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                    @else
                      <span style="color:var(--success);">Rp 0</span>
                    @endif
                  </td>
                  <td style="padding:12px 14px;">
                    <a href="{{ route('pengembalian.show', $p) }}"
                       style="background:linear-gradient(135deg,var(--success),var(--teal));color:white;padding:6px 14px;border-radius:6px;font-size:0.8rem;font-weight:600;display:inline-block;">
                      Kembalikan
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="padding:16px 24px;border-top:1px solid var(--border);">
          {{ $peminjaman->links() }}
        </div>
      </div>
    @endif

  </div>
</section>
@endsection
