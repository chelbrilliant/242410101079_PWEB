@extends('layouts.app')

@section('title', 'Data Peminjaman — Sistem Informasi Perpustakaan')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #2e86c1 100%); min-height: 160px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">📋 Manajemen Peminjaman</div>
      <h2>Data Peminjaman<br/>Buku</h2>
      <p>Kelola seluruh data peminjaman, kembalikan buku, dan pantau status peminjaman secara real-time.</p>
      <div class="beranda-cta">
        <a href="{{ route('peminjaman.create') }}" class="cta-btn cta-primary">➕ Tambah Peminjaman</a>
        <a href="{{ route('beranda') }}" class="cta-btn cta-secondary">🏠 Kembali ke Beranda</a>
      </div>
    </div>
    <div class="beranda-hero-art">
      <div class="hero-book-icon">📚</div>
    </div>
  </div>

  {{-- Tabel Data Peminjaman --}}
  <div style="padding: 32px; max-width: 1200px; margin: 0 auto;">

    {{-- Info jumlah data --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
      <p style="color: var(--text-muted); font-size: 0.9rem;">
        Menampilkan <strong>{{ $peminjaman->firstItem() }}–{{ $peminjaman->lastItem() }}</strong>
        dari <strong>{{ $peminjaman->total() }}</strong> data peminjaman
      </p>
      <a href="{{ route('peminjaman.create') }}"
         style="background: var(--accent); color: white; padding: 9px 20px; border-radius: 8px; font-size: 0.9rem; font-weight: 600; display:inline-flex; align-items:center; gap:6px; transition: var(--transition);"
         onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
        ➕ Tambah Baru
      </a>
    </div>

    <div style="background: white; border-radius: var(--radius); box-shadow: var(--shadow-md); overflow: hidden;">
      <div style="background: linear-gradient(135deg, var(--primary-dark), var(--primary)); padding: 16px 24px;">
        <h3 style="color: white; font-size: 1rem; font-weight: 600; margin: 0;">📋 Data Peminjaman Terkini</h3>
      </div>

      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: #f0f4f8;">
              <th style="padding: 12px 16px; text-align:left; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border);">NO</th>
              <th style="padding: 12px 16px; text-align:left; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border);">ID ANGGOTA</th>
              <th style="padding: 12px 16px; text-align:left; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border);">NAMA PEMINJAM</th>
              <th style="padding: 12px 16px; text-align:left; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border);">JUDUL BUKU</th>
              <th style="padding: 12px 16px; text-align:left; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border);">TGL PINJAM</th>
              <th style="padding: 12px 16px; text-align:left; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border);">STATUS</th>
              <th style="padding: 12px 16px; text-align:center; font-size: 0.82rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border);">AKSI</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($peminjaman as $index => $item)
              <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;"
                  onmouseover="this.style.background='#f8fbff'" onmouseout="this.style.background='white'">
                <td style="padding: 13px 16px; font-size: 0.88rem; color: var(--text-muted);">
                  {{ $peminjaman->firstItem() + $index }}
                </td>
                <td style="padding: 13px 16px; font-size: 0.88rem; font-weight: 600; color: var(--primary);">
                  {{ $item->id_anggota }}
                </td>
                <td style="padding: 13px 16px; font-size: 0.88rem;">
                  <div style="display:flex; align-items:center; gap:10px;">
                    @if($item->foto)
                      <img src="{{ asset('storage/' . $item->foto) }}"
                           style="width:34px; height:34px; border-radius:50%; object-fit:cover; border: 2px solid var(--border);"
                           alt="Foto {{ $item->nama_peminjam }}" />
                    @else
                      <div style="width:34px; height:34px; border-radius:50%; background: linear-gradient(135deg, var(--primary), var(--accent)); display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:0.8rem;">
                        {{ strtoupper(substr($item->nama_peminjam, 0, 1)) }}
                      </div>
                    @endif
                    {{ $item->nama_peminjam }}
                  </div>
                </td>
                <td style="padding: 13px 16px; font-size: 0.88rem;">{{ Str::limit($item->judul_buku, 35) }}</td>
                <td style="padding: 13px 16px; font-size: 0.88rem; color: var(--text-muted);">
                  {{ $item->tanggal_pinjam ? $item->tanggal_pinjam->format('d/m/Y') : '-' }}
                </td>
                <td style="padding: 13px 16px;">
                  @php
                    $badgeStyle = match($item->status) {
                        'Dipinjam'    => 'background:#fff3cd;color:#856404;border:1px solid #ffc107;',
                        'Dikembalikan'=> 'background:#d4edda;color:#155724;border:1px solid #28a745;',
                        'Terlambat'   => 'background:#f8d7da;color:#721c24;border:1px solid #dc3545;',
                        default       => 'background:#e2e3e5;color:#383d41;border:1px solid #adb5bd;',
                    };
                  @endphp
                  <span style="padding: 3px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; {{ $badgeStyle }}">
                    {{ $item->status }}
                  </span>
                </td>
                <td style="padding: 13px 16px; text-align: center;">
                  <div style="display:flex; gap:6px; justify-content:center;">
                    <a href="{{ route('peminjaman.show', $item) }}"
                       style="background:#17a589;color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;" title="Detail">👁 Detail</a>
                    <a href="{{ route('peminjaman.edit', $item) }}"
                       style="background:#2e86c1;color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;" title="Edit">✏️ Edit</a>
                    <form action="{{ route('peminjaman.destroy', $item) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              onclick="return confirmDelete('{{ $item->nama_peminjam }}')"
                              style="background:#c0392b;color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;border:none;cursor:pointer;">
                        🗑 Hapus
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" style="padding: 48px; text-align:center; color: var(--text-muted);">
                  <div style="font-size: 2.5rem; margin-bottom: 10px;">📭</div>
                  <p style="font-weight: 600;">Belum ada data peminjaman.</p>
                  <a href="{{ route('peminjaman.create') }}" style="color: var(--primary-light); font-size: 0.9rem;">+ Tambah peminjaman pertama</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if ($peminjaman->hasPages())
        <div style="padding: 20px 24px; border-top: 1px solid var(--border); display:flex; justify-content:center;">
          {{ $peminjaman->links() }}
        </div>
      @endif
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
  // Konfirmasi hapus dengan JavaScript
  function confirmDelete(nama) {
    return confirm('Yakin ingin menghapus data peminjaman atas nama "' + nama + '"?\n\nData yang dihapus tidak dapat dikembalikan.');
  }

  document.addEventListener('DOMContentLoaded', () => {
    console.log('Halaman Index Peminjaman loaded');
  });
</script>
@endpush
