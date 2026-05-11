@extends('layouts.app')

@section('title', 'Detail Peminjaman — {{ $peminjaman->nama_peminjam }}')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background: linear-gradient(135deg, #4a235a 0%, #7d3c98 100%); min-height: 130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">👁 Detail Data</div>
      <h2>Detail Peminjaman<br/>Buku</h2>
      <p>Informasi lengkap peminjaman buku oleh <strong>{{ $peminjaman->nama_peminjam }}</strong>.</p>
      <div class="beranda-cta">
        <a href="{{ route('peminjaman.edit', $peminjaman) }}" class="cta-btn cta-primary">✏️ Edit Data</a>
        <a href="{{ route('peminjaman.index') }}" class="cta-btn cta-secondary">⬅ Kembali</a>
      </div>
    </div>
    <div class="beranda-hero-art">
      <div class="hero-book-icon">🔍</div>
    </div>
  </div>

  <div style="padding: 32px; max-width: 780px; margin: 0 auto;">

    {{-- Card Detail --}}
    <div style="background: white; border-radius: var(--radius); box-shadow: var(--shadow-md); overflow:hidden; margin-bottom: 24px;">
      <div style="background: linear-gradient(135deg, #4a235a, #7d3c98); padding: 16px 28px; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="color: white; margin:0; font-size: 1rem;">📋 Informasi Peminjaman #{{ $peminjaman->id }}</h3>
        @php
          $badgeStyle = match($peminjaman->status) {
              'Dipinjam'    => 'background:#ffc107;color:#212529;',
              'Dikembalikan'=> 'background:#28a745;color:white;',
              'Terlambat'   => 'background:#dc3545;color:white;',
              default       => 'background:#6c757d;color:white;',
          };
        @endphp
        <span style="padding: 5px 16px; border-radius: 20px; font-size: 0.82rem; font-weight: 700; {{ $badgeStyle }}">
          {{ $peminjaman->status }}
        </span>
      </div>

      <div style="padding: 28px;">
        <div style="display: flex; gap: 28px; align-items: flex-start; margin-bottom: 28px; padding-bottom: 24px; border-bottom: 1px solid var(--border);">
          {{-- Avatar/Foto --}}
          <div style="flex-shrink: 0;">
            @if($peminjaman->foto)
              <img src="{{ asset('storage/' . $peminjaman->foto) }}"
                   style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid var(--border); box-shadow: var(--shadow-sm);" />
            @else
              <div style="width:100px; height:100px; border-radius:50%; background: linear-gradient(135deg, #7d3c98, var(--accent)); display:flex; align-items:center; justify-content:center; color:white; font-size: 2.2rem; font-weight:700; box-shadow: var(--shadow-sm);">
                {{ strtoupper(substr($peminjaman->nama_peminjam, 0, 1)) }}
              </div>
            @endif
          </div>
          {{-- Info utama --}}
          <div>
            <h2 style="font-size: 1.3rem; font-family: var(--font-display); color: var(--text); margin: 0 0 4px;">{{ $peminjaman->nama_peminjam }}</h2>
            <p style="color: var(--primary-light); font-weight: 600; font-size: 0.95rem; margin: 0 0 6px;">{{ $peminjaman->id_anggota }}</p>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0;">
              Dipinjam pada {{ $peminjaman->tanggal_pinjam ? $peminjaman->tanggal_pinjam->format('d F Y') : '-' }}
            </p>
          </div>
        </div>

        {{-- Grid detail --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          @php
            $fields = [
              ['label' => '📚 Judul Buku',       'value' => $peminjaman->judul_buku],
              ['label' => '🔖 Kode Buku',         'value' => $peminjaman->kode_buku ?: '-'],
              ['label' => '📅 Tanggal Pinjam',    'value' => $peminjaman->tanggal_pinjam?->format('d F Y') ?: '-'],
              ['label' => '📅 Tanggal Kembali',   'value' => $peminjaman->tanggal_kembali?->format('d F Y') ?: 'Belum dikembalikan'],
              ['label' => '✅ Aktif',              'value' => $peminjaman->aktif ? 'Ya' : 'Tidak'],
              ['label' => '🕐 Dibuat',             'value' => $peminjaman->created_at->format('d F Y, H:i')],
            ];
          @endphp
          @foreach($fields as $field)
            <div style="padding: 14px 18px; background: #f8fbff; border-radius: 8px; border-left: 3px solid var(--primary-light);">
              <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.5px;">{{ $field['label'] }}</p>
              <p style="font-size: 0.92rem; font-weight: 600; margin: 0; color: var(--text);">{{ $field['value'] }}</p>
            </div>
          @endforeach

          {{-- Keterangan full width --}}
          @if($peminjaman->keterangan)
          <div style="grid-column: 1 / -1; padding: 14px 18px; background: #f8fbff; border-radius: 8px; border-left: 3px solid var(--primary-light);">
            <p style="font-size: 0.78rem; color: var(--text-muted); margin: 0 0 4px; text-transform: uppercase; letter-spacing: 0.5px;">📝 Keterangan</p>
            <p style="font-size: 0.92rem; margin: 0; color: var(--text);">{{ $peminjaman->keterangan }}</p>
          </div>
          @endif
        </div>

        {{-- Action buttons --}}
        <div style="display:flex; gap:12px; margin-top:28px; padding-top:20px; border-top:1px solid var(--border);">
          <a href="{{ route('peminjaman.edit', $peminjaman) }}"
             style="background: linear-gradient(135deg, #1a5276, #2e86c1); color:white; padding:11px 24px; border-radius:8px; font-size:0.9rem; font-weight:600;">
            ✏️ Edit Data Ini
          </a>
          <form action="{{ route('peminjaman.destroy', $peminjaman) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit"
                    onclick="return confirm('Yakin hapus data peminjaman ini?')"
                    style="background:#c0392b; color:white; padding:11px 24px; border:none; border-radius:8px; font-size:0.9rem; font-weight:600; cursor:pointer;">
              🗑 Hapus
            </button>
          </form>
          <a href="{{ route('peminjaman.index') }}"
             style="background:#f0f4f8; color:var(--text); padding:11px 22px; border-radius:8px; font-size:0.9rem; font-weight:600;">
            ⬅ Kembali ke Daftar
          </a>
        </div>
      </div>
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
  console.log('Halaman Show Peminjaman loaded');
</script>
@endpush
