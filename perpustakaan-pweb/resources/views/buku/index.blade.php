@extends('layouts.app')
@section('title', 'Daftar Buku — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background:linear-gradient(135deg,#145a32 0%,#1e8449 60%,#27ae60 100%);min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Koleksi Buku</div>
      <h2>Daftar Buku<br/>Perpustakaan</h2>
      <p>Telusuri koleksi buku yang tersedia. Total {{ \App\Models\Buku::count() }} judul buku.</p>
      <div class="beranda-cta">
        @auth
          <a href="{{ route('peminjaman.create') }}" class="cta-btn cta-primary">Pinjam Buku</a>
        @endauth
        @if(auth()->check() && auth()->user()->isAdmin())
          <a href="{{ route('buku.create') }}" class="cta-btn cta-secondary">Tambah Buku</a>
        @endif
      </div>
    </div>
    <div class="beranda-hero-art"><div class="hero-book-icon"></div></div>
  </div>

  <div style="padding:24px 20px;max-width:1100px;margin:0 auto;">

    {{-- Search --}}
    <div style="display:flex;gap:12px;margin-bottom:24px;">
      <form method="GET" action="{{ route('buku.index') }}" style="display:flex;gap:12px;flex:1;">
        <input type="text" name="search" value="{{ $keyword }}"
               placeholder="Cari judul, pengarang, kategori, atau kode buku..."
               style="flex:1;padding:11px 16px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
        <button type="submit"
                style="background:var(--primary);color:white;padding:11px 22px;border:none;border-radius:8px;font-weight:600;cursor:pointer;">
          Cari
        </button>
        @if($keyword)
          <a href="{{ route('buku.index') }}"
             style="background:#f0f4f8;color:var(--text);padding:11px 16px;border-radius:8px;font-weight:600;display:flex;align-items:center;">
            Reset
          </a>
        @endif
      </form>
    </div>

    {{-- Tabel buku --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#145a32,#1e8449);padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="color:white;margin:0;font-size:1rem;">Koleksi Buku ({{ $buku->total() }} buku)</h3>
        @if(auth()->check() && auth()->user()->isAdmin())
          <a href="{{ route('buku.create') }}" style="background:rgba(255,255,255,0.2);color:white;padding:6px 14px;border-radius:6px;font-size:0.82rem;">
            Tambah Buku
          </a>
        @endif
      </div>

      @if($buku->isEmpty())
        <div style="padding:40px;text-align:center;color:var(--text-muted);">
          {{ $keyword ? 'Tidak ada buku yang cocok dengan "' . $keyword . '"' : 'Belum ada buku.' }}
        </div>
      @else
        <div style="overflow-x:auto;">
          <table style="width:100%;border-collapse:collapse;">
            <thead>
              <tr style="background:#f0f4f8;">
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">NO</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">KODE</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">JUDUL BUKU</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">PENGARANG</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">KATEGORI</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">STOK</th>
                <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">STATUS</th>
                @if(auth()->check() && auth()->user()->isAdmin())
                  <th style="padding:12px 16px;text-align:left;font-size:0.78rem;color:var(--text-muted);">AKSI</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($buku as $i => $b)
                <tr style="border-bottom:1px solid var(--border);">
                  <td style="padding:12px 16px;font-size:0.85rem;color:var(--text-muted);">{{ $buku->firstItem() + $i }}</td>
                  <td style="padding:12px 16px;font-size:0.85rem;font-weight:600;color:var(--primary);">{{ $b->kode_buku }}</td>
                  <td style="padding:12px 16px;font-size:0.9rem;font-weight:600;">
                    <a href="{{ route('buku.show', $b) }}" style="color:var(--primary-light);">{{ $b->judul }}</a>
                  </td>
                  <td style="padding:12px 16px;font-size:0.85rem;">{{ $b->pengarang }}</td>
                  <td style="padding:12px 16px;">
                    <span style="background:#e8f0fe;color:#1a73e8;padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;">{{ $b->kategori }}</span>
                  </td>
                  <td style="padding:12px 16px;font-size:0.9rem;font-weight:700;color:{{ $b->stok > 0 ? 'var(--success)' : 'var(--danger)' }};">
                    {{ $b->stok }}
                  </td>
                  <td style="padding:12px 16px;">
                    @if($b->stok > 0 && $b->tersedia)
                      <span style="background:#d4edda;color:#155724;padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;">Tersedia</span>
                    @else
                      <span style="background:#f8d7da;color:#721c24;padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;">Habis</span>
                    @endif
                  </td>
                  @if(auth()->check() && auth()->user()->isAdmin())
                    <td style="padding:12px 16px;">
                      <div style="display:flex;gap:6px;">
                        <a href="{{ route('buku.edit', $b) }}"
                           style="background:#2e86c1;color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;">
                          Edit
                        </a>
                        <form action="{{ route('buku.destroy', $b) }}" method="POST" style="display:flex;margin:0;padding:0;>
                          @csrf @method('DELETE')
                          <button type="button"
                                  onclick="konfirmasiHapusBuku('{{ $b->judul }}', this.closest('form'))"
                                  style="background:#c0392b;color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;border:none;cursor:pointer;">
                            Hapus
                          </button>
                        </form>
                      </div>
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="padding:16px 24px;border-top:1px solid var(--border);">
          {{ $buku->links() }}
        </div>
      @endif
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
function konfirmasiHapusBuku(judul, form) {
  const ok = confirm(`Yakin ingin menghapus buku "${judul}"?\n\nData yang dihapus tidak bisa dikembalikan.`);
  if (ok) form.submit();
}
</script>
@endpush
