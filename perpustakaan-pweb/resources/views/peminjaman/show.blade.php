@extends('layouts.app')
@section('title', 'Detail Peminjaman #' . $peminjaman->id . ' — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">
  <div class="beranda-hero" style="background:linear-gradient(135deg,#4a235a,#7d3c98);min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Detail Peminjaman</div>
      <h2>Peminjaman #{{ $peminjaman->id }}</h2>
      <p>Detail peminjaman buku oleh <strong>{{ $peminjaman->nama_peminjam }}</strong>.</p>
      <div class="beranda-cta">
        @if($peminjaman->status !== 'Dikembalikan')
          <a href="{{ route('pengembalian.show', $peminjaman) }}" class="cta-btn cta-primary">Kembalikan Buku</a>
        @endif
        <a href="{{ route('peminjaman.index') }}" class="cta-btn cta-secondary">Kembali</a>
      </div>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:780px;margin:0 auto;">

    {{-- Card utama --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
      <div style="background:linear-gradient(135deg,#4a235a,#7d3c98);padding:16px 28px;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="color:white;margin:0;">Informasi Peminjaman</h3>
        @php
          $badgeStyle = match($peminjaman->status) {
            'Dipinjam'     => 'background:#ffc107;color:#212529;',
            'Dikembalikan' => 'background:#28a745;color:white;',
            'Terlambat'    => 'background:#dc3545;color:white;',
            default        => 'background:#6c757d;color:white;',
          };
        @endphp
        <span style="padding:5px 16px;border-radius:20px;font-size:0.82rem;font-weight:700;{{ $badgeStyle }}">
          {{ $peminjaman->status }}
        </span>
      </div>

      <div style="padding:28px;">
        {{-- Header info peminjam --}}
        <div style="display:flex;gap:20px;align-items:flex-start;margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border);">
          @if($peminjaman->foto)
            <img src="{{ asset('storage/' . $peminjaman->foto) }}"
                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--border);" />
          @else
            <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#7d3c98,#4a235a);display:flex;align-items:center;justify-content:center;color:white;font-size:2rem;font-weight:700;flex-shrink:0;">
              {{ strtoupper(substr($peminjaman->nama_peminjam, 0, 1)) }}
            </div>
          @endif
          <div>
            <h2 style="font-size:1.2rem;font-family:var(--font-display);margin:0 0 4px;">{{ $peminjaman->nama_peminjam }}</h2>
            <p style="color:var(--primary-light);font-weight:600;font-size:0.9rem;margin:0 0 4px;">{{ $peminjaman->id_anggota }}</p>
            <p style="color:var(--text-muted);font-size:0.82rem;margin:0;">Dibuat: {{ $peminjaman->created_at->format('d F Y, H:i') }}</p>
          </div>
        </div>

        {{-- Grid detail --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;">
          @php
            $fields = [
              ['label' => 'Judul Buku',      'value' => $peminjaman->judul_buku],
              ['label' => 'Kode Buku',        'value' => $peminjaman->kode_buku ?? '-'],
              ['label' => 'Tanggal Pinjam',   'value' => $peminjaman->tanggal_pinjam?->format('d F Y') ?? '-'],
              ['label' => 'Tanggal Kembali',  'value' => $peminjaman->tanggal_kembali?->format('d F Y') ?? '-'],
            ];
          @endphp
          @foreach($fields as $f)
            <div style="padding:14px 18px;background:#f8fbff;border-radius:8px;border-left:3px solid var(--primary-light);">
              <p style="font-size:0.75rem;color:var(--text-muted);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.5px;">{{ $f['label'] }}</p>
              <p style="font-size:0.92rem;font-weight:600;margin:0;color:var(--text);">{{ $f['value'] }}</p>
            </div>
          @endforeach
        </div>

        {{-- Info keterlambatan & denda --}}
        @php
          $keterlambatan = $peminjaman->hitungKeterlambatan();
          $denda         = $peminjaman->hitungDenda();
        @endphp

        @if($peminjaman->status !== 'Dikembalikan' && $keterlambatan > 0)
          <div style="background:#f8d7da;border:1px solid #f5c6cb;border-radius:8px;padding:16px 20px;margin-bottom:20px;" id="dendaBox">
            <p style="font-weight:700;color:#721c24;margin:0 0 8px;">Buku Terlambat Dikembalikan!</p>
            <p style="color:#721c24;margin:0 0 4px;font-size:0.9rem;">Keterlambatan: <strong id="hariTerlambat">{{ $keterlambatan }}</strong> hari</p>
            <p style="color:#721c24;margin:0;font-size:0.9rem;">Estimasi denda: <strong id="jumlahDenda">Rp {{ number_format($denda, 0, ',', '.') }}</strong></p>
            <p style="color:#999;font-size:0.78rem;margin-top:6px;">* Denda dihitung Rp 1.000/hari. Dibayar langsung di perpustakaan.</p>
          </div>
        @elseif($peminjaman->status === 'Dikembalikan' && $peminjaman->denda > 0)
          <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:16px 20px;margin-bottom:20px;">
            <p style="font-weight:700;color:#856404;margin:0 0 6px;">Denda Keterlambatan</p>
            <p style="color:#856404;margin:0;font-size:0.9rem;">
              Total denda: <strong>Rp {{ number_format($peminjaman->denda, 0, ',', '.') }}</strong>
              @if($peminjaman->denda_dibayar)
                <span style="background:#d4edda;color:#155724;padding:2px 8px;border-radius:10px;font-size:0.78rem;margin-left:8px;">Lunas</span>
              @else
                <span style="background:#f8d7da;color:#721c24;padding:2px 8px;border-radius:10px;font-size:0.78rem;margin-left:8px;">Belum Dibayar</span>
              @endif
            </p>
          </div>
        @endif

        @if($peminjaman->keterangan)
          <div style="padding:14px 18px;background:#f8fbff;border-radius:8px;border-left:3px solid var(--primary-light);margin-bottom:20px;">
            <p style="font-size:0.75rem;color:var(--text-muted);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.5px;">Keterangan</p>
            <p style="font-size:0.92rem;margin:0;color:var(--text);">{{ $peminjaman->keterangan }}</p>
          </div>
        @endif

        {{-- Tombol aksi --}}
        <div style="display:flex;gap:10px;flex-wrap:wrap;padding-top:16px;border-top:1px solid var(--border);">
          @if($peminjaman->status !== 'Dikembalikan')
            <a href="{{ route('pengembalian.show', $peminjaman) }}"
               style="background:linear-gradient(135deg,var(--success),var(--teal));color:white;padding:10px 22px;border-radius:8px;font-size:0.88rem;font-weight:600;">
              Kembalikan Buku
            </a>
            <a href="{{ route('peminjaman.edit', $peminjaman) }}"
               style="background:linear-gradient(135deg,#1a5276,#2e86c1);color:white;padding:10px 22px;border-radius:8px;font-size:0.88rem;font-weight:600;">
              Edit
            </a>
          @endif

          {{-- Admin: tandai denda lunas --}}
          @if(auth()->user()->isAdmin() && $peminjaman->status === 'Dikembalikan' && $peminjaman->denda > 0 && !$peminjaman->denda_dibayar)
            <form action="{{ route('pengembalian.bayar-denda', $peminjaman) }}" method="POST" style="display:inline;">
              @csrf @method('PATCH')
              <button type="submit"
                      style="background:linear-gradient(135deg,#f39c12,#d68910);color:white;padding:10px 22px;border:none;border-radius:8px;font-size:0.88rem;font-weight:600;cursor:pointer;font-family:var(--font-body);">
                Tandai Denda Lunas
              </button>
            </form>
          @endif

          @if(auth()->user()->isAdmin() || $peminjaman->status !== 'Dikembalikan')
            <form action="{{ route('peminjaman.destroy', $peminjaman) }}" method="POST" style="display:inline;">
                @csrf @method('DELETE')
                <button type="button"
                        onclick="if(confirm('Yakin hapus peminjaman ini?')) this.closest('form').submit()"
                        style="background:#c0392b;color:white;padding:10px 22px;border:none;border-radius:8px;font-size:0.88rem;font-weight:600;cursor:pointer;font-family:var(--font-body);">
                    Hapus
                </button>
            </form>
        @endif

          <a href="{{ route('peminjaman.index') }}"
             style="background:#f0f4f8;color:var(--text);padding:10px 18px;border-radius:8px;font-size:0.88rem;font-weight:600;">
            Kembali
          </a>
        </div>
      </div>
    </div>

    {{-- Bukti Peminjaman (untuk ditunjukkan ke admin di perpustakaan) --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;border:2px dashed var(--border);" id="buktiPeminjaman">
      <div style="background:#f0f4f8;padding:14px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
        <h4 style="margin:0;font-size:0.95rem;color:var(--text);">Bukti Peminjaman</h4>
        <span style="font-size:0.78rem;color:var(--text-muted);">Tunjukkan ini ke petugas perpustakaan</span>
      </div>
      <div style="padding:20px 24px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:20px;">
          <div>
            <p style="font-size:0.75rem;color:var(--text-muted);margin:0 0 2px;text-transform:uppercase;">Perpustakaan UPA UNEJ</p>
            <p style="font-size:1rem;font-weight:700;margin:0 0 8px;color:var(--primary);">Bukti Peminjaman #{{ $peminjaman->id }}</p>
            <p style="font-size:0.85rem;margin:2px 0;"><strong>{{ $peminjaman->judul_buku }}</strong></p>
            <p style="font-size:0.85rem;margin:2px 0;">{{ $peminjaman->nama_peminjam }}</p>
            <p style="font-size:0.85rem;margin:2px 0;">Pinjam: {{ $peminjaman->tanggal_pinjam?->format('d/m/Y') }} &nbsp;→&nbsp; Kembali: {{ $peminjaman->tanggal_kembali?->format('d/m/Y') }}</p>
          </div>
          <div style="text-align:right;">
            @php
              $colorStatus = match($peminjaman->status) {
                'Dipinjam'     => '#856404',
                'Dikembalikan' => '#155724',
                'Terlambat'    => '#721c24',
                default        => '#383d41',
              };
              $bgStatus = match($peminjaman->status) {
                'Dipinjam'     => '#fff3cd',
                'Dikembalikan' => '#d4edda',
                'Terlambat'    => '#f8d7da',
                default        => '#e2e3e5',
              };
            @endphp
            <span style="background:{{ $bgStatus }};color:{{ $colorStatus }};padding:6px 16px;border-radius:20px;font-size:0.88rem;font-weight:700;display:block;margin-bottom:8px;">
              {{ $peminjaman->status }}
            </span>
            <p style="font-size:0.75rem;color:var(--text-muted);margin:0;">ID: {{ $peminjaman->id_anggota }}</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</section>
@endsection

@push('scripts')
<script>
// Update denda secara real-time setiap menit (AJAX)
@if($peminjaman->status !== 'Dikembalikan' && $peminjaman->tanggal_kembali)
async function updateDenda() {
  try {
    const res  = await fetch('/pengembalian/{{ $peminjaman->id }}/cek', {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    const json = await res.json();
    const hariEl  = document.getElementById('hariTerlambat');
    const dendaEl = document.getElementById('jumlahDenda');
    const box     = document.getElementById('dendaBox');

    if (json.is_terlambat && hariEl && dendaEl) {
      hariEl.textContent  = json.keterlambatan;
      dendaEl.textContent = json.denda_format;
      if (box) box.style.display = 'block';
    }
  } catch(e) { /* diam saja jika gagal */ }
}

// Update setiap 60 detik
setInterval(updateDenda, 60000);
@endif
</script>
@endpush
