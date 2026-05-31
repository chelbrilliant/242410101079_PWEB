@extends('layouts.app')
@section('title', 'Peminjaman — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background:linear-gradient(135deg,#4a235a,#7d3c98);min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Peminjaman Buku</div>
      <h2>{{ auth()->user()->isAdmin() ? 'Semua Peminjaman' : 'Peminjaman Kamu' }}</h2>
      <p>{{ auth()->user()->isAdmin() ? 'Kelola semua data peminjaman buku dari seluruh customer.' : 'Daftar peminjaman buku yang telah kamu buat.' }}</p>
      <div class="beranda-cta">
        <a href="{{ route('peminjaman.create') }}" class="cta-btn cta-primary">Pinjam Buku</a>
        <a href="{{ route('pengembalian.index') }}" class="cta-btn cta-secondary">Pengembalian</a>
      </div>
    </div>
  </div>

  <div style="padding:24px 20px;max-width:1100px;margin:0 auto;">

    {{-- Live Search AJAX --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:18px 24px;margin-bottom:20px;border:1px solid var(--border);">
      <div style="display:flex;gap:12px;align-items:center;">
        <span style="font-size:0.85rem;color:var(--text-muted);white-space:nowrap;">Cari:</span>
        <input type="text" id="searchInput"
               placeholder="Nama peminjam, judul buku, ID anggota..."
               style="flex:1;padding:9px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
               oninput="liveSearch(this.value)" />
        <button onclick="resetSearch()"
                style="background:#f0f4f8;color:var(--text);padding:9px 14px;border:none;border-radius:8px;font-size:0.85rem;font-weight:600;cursor:pointer;white-space:nowrap;">
          Reset
        </button>
      </div>
      <div id="searchStatus" style="display:none;font-size:0.82rem;color:var(--text-muted);margin-top:8px;"></div>
      <div id="searchLoading" style="display:none;padding:10px 0;font-size:0.85rem;color:var(--text-muted);">Mencari...</div>
      <div id="searchResults"></div>
    </div>

    {{-- Tabel peminjaman --}}
    <div id="mainTable" style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#4a235a,#7d3c98);padding:16px 24px;display:flex;justify-content:space-between;align-items:center;">
        <h3 style="color:white;margin:0;">Data Peminjaman ({{ $peminjaman->total() }})</h3>
        <a href="{{ route('peminjaman.create') }}" style="background:rgba(255,255,255,0.2);color:white;padding:6px 14px;border-radius:6px;font-size:0.82rem;">
          Tambah
        </a>
      </div>

      @if($peminjaman->isEmpty())
        <div style="padding:40px;text-align:center;color:var(--text-muted);">
          Belum ada data peminjaman.
          <br><a href="{{ route('peminjaman.create') }}" style="color:var(--primary-light);font-weight:600;">Buat peminjaman</a>
        </div>
      @else
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
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">TGL PINJAM</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">TGL KEMBALI</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">STATUS</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">DENDA</th>
                <th style="padding:12px 14px;text-align:left;font-size:0.77rem;color:var(--text-muted);">AKSI</th>
              </tr>
            </thead>
            <tbody>
              @foreach($peminjaman as $i => $p)
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
                  <td style="padding:11px 14px;font-size:0.84rem;color:var(--text-muted);">{{ $peminjaman->firstItem() + $i }}</td>
                  <td style="padding:11px 14px;font-size:0.82rem;font-weight:600;color:var(--primary);">{{ $p->id_anggota }}</td>
                  @if(auth()->user()->isAdmin())
                    <td style="padding:11px 14px;font-size:0.85rem;font-weight:600;">{{ $p->nama_peminjam }}</td>
                  @endif
                  <td style="padding:11px 14px;font-size:0.85rem;">{{ $p->judul_buku }}</td>
                  <td style="padding:11px 14px;font-size:0.84rem;">{{ $p->tanggal_pinjam?->format('d/m/Y') }}</td>
                  <td style="padding:11px 14px;font-size:0.84rem;">
                    {{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}
                    @if($p->isTerlambat())
                      <span style="display:block;font-size:0.72rem;color:var(--danger);font-weight:600;">{{ $p->hitungKeterlambatan() }} hari terlambat</span>
                    @endif
                  </td>
                  <td style="padding:11px 14px;">
                    <span style="padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;{{ $badgeStyle }}">{{ $p->status }}</span>
                  </td>
                  <td style="padding:11px 14px;font-size:0.84rem;">
                    @if($denda > 0)
                      <span style="color:var(--danger);font-weight:600;">Rp {{ number_format($denda, 0, ',', '.') }}</span>
                    @else
                      <span style="color:var(--text-muted);">-</span>
                    @endif
                  </td>
                  <td style="padding:11px 14px;">
                    <div style="display:flex;gap:4px;flex-wrap:wrap;">
                      <a href="{{ route('peminjaman.show', $p) }}"
                         style="background:var(--primary);color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;">
                        Detail
                      </a>
                      @if($p->status !== 'Dikembalikan')
                        <a href="{{ route('peminjaman.edit', $p) }}"
                           style="background:#2e86c1;color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;">
                          Ubah
                        </a>
                        <a href="{{ route('pengembalian.show', $p) }}"
                           style="background:var(--success);color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;">
                          Kembalikan
                        </a>
                      @endif
                      @if(auth()->user()->isAdmin() || $p->status !== 'Dikembalikan')
                        <form action="{{ route('peminjaman.destroy', $p) }}" method="POST" style="display:flex;margin:0;padding:0;">
                            @csrf @method('DELETE')
                            <button type="button"
                                    onclick="konfirmasiHapus('{{ $p->nama_peminjam }}', '{{ $p->judul_buku }}', this.closest('form'))"
                                    style="background:#c0392b;color:white;padding:5px 12px;border-radius:6px;font-size:0.78rem;font-weight:600;border:none;cursor:pointer;">
                                Hapus
                            </button>
                        </form>
                    @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div style="padding:16px 24px;border-top:1px solid var(--border);">
          {{ $peminjaman->links() }}
        </div>
      @endif
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
let searchTimeout = null;

function liveSearch(keyword) {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => doSearch(keyword), 400);
}

async function doSearch(keyword) {
  const status  = document.getElementById('searchStatus');
  const loading = document.getElementById('searchLoading');
  const results = document.getElementById('searchResults');
  const main    = document.getElementById('mainTable');

  if (!keyword.trim()) {
    results.innerHTML = '';
    status.style.display = 'none';
    main.style.display   = 'block';
    return;
  }

  loading.style.display = 'block';
  main.style.display    = 'none';
  results.innerHTML = '';

  try {
    const response = await fetch(`/peminjaman-search?keyword=${encodeURIComponent(keyword)}`, {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    const json = await response.json();
    loading.style.display = 'none';
    status.style.display  = 'block';
    status.textContent    = `${json.total} hasil untuk "${json.keyword}"`;

    if (!json.data.length) {
      results.innerHTML = `<div style="padding:20px;text-align:center;color:var(--text-muted);">Tidak ditemukan hasil untuk "${keyword}"</div>`;
      return;
    }

    const bs = { 'Dipinjam':'background:#fff3cd;color:#856404;', 'Dikembalikan':'background:#d4edda;color:#155724;', 'Terlambat':'background:#f8d7da;color:#721c24;' };
    results.innerHTML = `<div style="margin-top:16px;border:1px solid var(--border);border-radius:8px;overflow:hidden;">
      <table style="width:100%;border-collapse:collapse;">
        <tbody>
          ${json.data.map((p,i) => `
            <tr style="border-bottom:1px solid var(--border);">
              <td style="padding:10px 14px;font-size:0.84rem;color:var(--text-muted);">${i+1}</td>
              <td style="padding:10px 14px;font-size:0.84rem;font-weight:600;color:var(--primary);">${p.id_anggota}</td>
              <td style="padding:10px 14px;font-size:0.84rem;">${p.nama_peminjam}</td>
              <td style="padding:10px 14px;font-size:0.84rem;">${p.judul_buku}</td>
              <td style="padding:10px 14px;"><span style="padding:3px 10px;border-radius:20px;font-size:0.78rem;font-weight:600;${bs[p.status]||''}">${p.status}</span></td>
              <td style="padding:10px 14px;"><a href="/peminjaman/${p.id}" style="font-size:0.8rem;color:var(--primary-light);font-weight:600;">Detail</a></td>
            </tr>`).join('')}
        </tbody>
      </table>
    </div>`;
  } catch(err) {
    loading.style.display = 'none';
    results.innerHTML = `<p style="color:var(--danger);padding:10px;">Error: ${err.message}</p>`;
  }
}

function resetSearch() {
  document.getElementById('searchInput').value = '';
  document.getElementById('searchResults').innerHTML = '';
  document.getElementById('searchStatus').style.display = 'none';
  document.getElementById('mainTable').style.display = 'block';
}

function konfirmasiHapus(nama, judul, form) {
  if (confirm(`Hapus peminjaman "${nama} — ${judul}"?\n\nJika masih berstatus Dipinjam, stok buku akan dikembalikan otomatis.`)) {
    form.submit();
  }
}
</script>
@endpush
