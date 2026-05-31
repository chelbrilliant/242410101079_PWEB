@extends('layouts.app')
@section('title', $buku->judul . ' — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">
  <div class="beranda-hero" style="background:linear-gradient(135deg,#145a32,#1e8449);min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Detail Buku</div>
      <h2>{{ $buku->judul }}</h2>
      <p>{{ $buku->pengarang }} &bull; {{ $buku->tahun_terbit }} &bull; {{ $buku->kategori }}</p>
      <div class="beranda-cta">
        @auth
          @if($buku->stok > 0 && $buku->tersedia)
            <a href="{{ route('peminjaman.create') }}?buku_id={{ $buku->id }}" class="cta-btn cta-primary">Pinjam Buku Ini</a>
          @endif
        @endauth
        <a href="{{ route('buku.index') }}" class="cta-btn cta-secondary">Kembali</a>
      </div>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:700px;margin:0 auto;">
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#145a32,#1e8449);padding:16px 28px;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="color:white;margin:0;">Informasi Buku</h3>
        @if($buku->stok > 0 && $buku->tersedia)
          <span style="background:rgba(46,204,113,0.25);color:#7dffbe;padding:5px 14px;border-radius:20px;font-size:0.8rem;font-weight:700;">Tersedia ({{ $buku->stok }} stok)</span>
        @else
          <span style="background:rgba(192,57,43,0.25);color:#f1948a;padding:5px 14px;border-radius:20px;font-size:0.8rem;font-weight:700;">Stok Habis</span>
        @endif
      </div>

      <div style="padding:32px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;">
          @php
            $fields = [
              ['label' => 'Kode Buku',   'value' => $buku->kode_buku],
              ['label' => 'Kategori',     'value' => $buku->kategori],
              ['label' => 'Judul',         'value' => $buku->judul, 'full' => true],
              ['label' => 'Pengarang',    'value' => $buku->pengarang],
              ['label' => 'Tahun Terbit', 'value' => $buku->tahun_terbit],
              ['label' => 'Stok',          'value' => $buku->stok . ' buku'],
            ];
          @endphp
          @foreach($fields as $f)
            <div style="{{ ($f['full'] ?? false) ? 'grid-column:1/-1;' : '' }}padding:14px 18px;background:#f8fbff;border-radius:8px;border-left:3px solid var(--primary-light);">
              <p style="font-size:0.76rem;color:var(--text-muted);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.5px;">{{ $f['label'] }}</p>
              <p style="font-size:0.95rem;font-weight:600;margin:0;color:var(--text);">{{ $f['value'] }}</p>
            </div>
          @endforeach
        </div>

        <div style="display:flex;gap:12px;padding-top:20px;border-top:1px solid var(--border);">
          @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('buku.edit', $buku) }}"
               style="background:linear-gradient(135deg,#1a5276,#2e86c1);color:white;padding:11px 24px;border-radius:8px;font-size:0.9rem;font-weight:600;">
              Edit Buku
            </a>
          @endif
          @auth
            @if($buku->stok > 0)
              <a href="{{ route('peminjaman.create') }}"
                 style="background:linear-gradient(135deg,var(--accent),var(--teal));color:white;padding:11px 24px;border-radius:8px;font-size:0.9rem;font-weight:600;">
                Pinjam Buku
              </a>
            @endif
          @endauth
          <a href="{{ route('buku.index') }}"
             style="background:#f0f4f8;color:var(--text);padding:11px 22px;border-radius:8px;font-size:0.9rem;font-weight:600;">
            Kembali
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
