@extends('layouts.app')
@section('title', 'Konfirmasi Pengembalian — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">
  <div class="beranda-hero" style="background:linear-gradient(135deg,#0b3d2e,#1e8449);min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Konfirmasi Pengembalian</div>
      <h2>Kembalikan Buku</h2>
      <p>Pastikan buku sudah dikembalikan secara fisik ke perpustakaan sebelum menekan tombol konfirmasi.</p>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:720px;margin:0 auto;">

    {{-- Card detail peminjaman --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
      <div style="background:linear-gradient(135deg,#0b3d2e,#1e8449);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Detail Peminjaman</h3>
      </div>
      <div style="padding:28px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
          @php
            $fields = [
              ['label' => 'Peminjam',        'value' => $peminjaman->nama_peminjam],
              ['label' => 'ID Anggota',       'value' => $peminjaman->id_anggota],
              ['label' => 'Judul Buku',       'value' => $peminjaman->judul_buku,  'full' => true],
              ['label' => 'Tanggal Pinjam',  'value' => $peminjaman->tanggal_pinjam?->format('d F Y') ?? '-'],
              ['label' => 'Batas Kembali',   'value' => $peminjaman->tanggal_kembali?->format('d F Y') ?? '-'],
            ];
          @endphp
          @foreach($fields as $f)
            <div style="{{ ($f['full'] ?? false) ? 'grid-column:1/-1;' : '' }}padding:14px 18px;background:#f8fbff;border-radius:8px;border-left:3px solid var(--primary-light);">
              <p style="font-size:0.75rem;color:var(--text-muted);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.5px;">{{ $f['label'] }}</p>
              <p style="font-size:0.92rem;font-weight:600;margin:0;color:var(--text);">{{ $f['value'] }}</p>
            </div>
          @endforeach
        </div>

        {{-- Info denda --}}
        @if($keterlambatan > 0)
          {{-- Terlambat: tampilkan denda --}}
          <div id="dendaBox"
               style="background:#f8d7da;border:1.5px solid #f5c6cb;border-radius:10px;padding:20px 24px;margin-bottom:20px;">
            <p style="font-weight:700;color:#721c24;font-size:1rem;margin:0 0 12px;">Buku Terlambat Dikembalikan!</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
              <div style="background:rgba(255,255,255,0.6);border-radius:8px;padding:12px 16px;text-align:center;">
                <p style="font-size:0.75rem;color:#721c24;margin:0 0 4px;text-transform:uppercase;">Hari Terlambat</p>
                <p id="hariTerlambat" style="font-size:1.8rem;font-weight:800;color:#c0392b;margin:0;">{{ $keterlambatan }}</p>
                <p style="font-size:0.75rem;color:#721c24;margin:0;">hari</p>
              </div>
              <div style="background:rgba(255,255,255,0.6);border-radius:8px;padding:12px 16px;text-align:center;">
                <p style="font-size:0.75rem;color:#721c24;margin:0 0 4px;text-transform:uppercase;">Total Denda</p>
                <p id="jumlahDenda" style="font-size:1.4rem;font-weight:800;color:#c0392b;margin:0;">Rp {{ number_format($denda, 0, ',', '.') }}</p>
                <p style="font-size:0.75rem;color:#721c24;margin:0;">Rp 1.000/hari</p>
              </div>
            </div>
            <p style="font-size:0.8rem;color:#856404;margin:12px 0 0;background:rgba(255,243,205,0.8);padding:8px 12px;border-radius:6px;">
              Denda dibayarkan langsung ke petugas perpustakaan. Sistem hanya mencatat jumlah denda.
            </p>
          </div>
        @else
          {{-- Belum terlambat --}}
          <div style="background:#d4edda;border:1.5px solid #c3e6cb;border-radius:10px;padding:16px 20px;margin-bottom:20px;">
            <p style="font-weight:700;color:#155724;margin:0 0 4px;">Pengembalian Tepat Waktu</p>
            <p style="color:#155724;font-size:0.88rem;margin:0;">Tidak ada denda. Terima kasih sudah mengembalikan buku tepat waktu!</p>
          </div>
        @endif

      </div>
    </div>

    {{-- Form konfirmasi pengembalian --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#0b3d2e,#1e8449);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Konfirmasi Pengembalian</h3>
      </div>
      <div style="padding:28px;">

        {{-- Checklist sebelum konfirmasi --}}
        <div style="margin-bottom:24px;">
          <p style="font-size:0.88rem;font-weight:600;color:var(--text);margin:0 0 12px;">Sebelum mengkonfirmasi, pastikan:</p>
          <label style="display:flex;align-items:center;gap:10px;margin-bottom:8px;cursor:pointer;">
            <input type="checkbox" id="cek1" onchange="cekSemua()" style="width:16px;height:16px;" />
            <span style="font-size:0.88rem;color:var(--text);">Buku sudah dikembalikan secara fisik ke perpustakaan</span>
          </label>
          <label style="display:flex;align-items:center;gap:10px;margin-bottom:8px;cursor:pointer;">
            <input type="checkbox" id="cek2" onchange="cekSemua()" style="width:16px;height:16px;" />
            <span style="font-size:0.88rem;color:var(--text);">Kondisi buku sudah diperiksa oleh petugas</span>
          </label>
          @if($keterlambatan > 0)
            <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
              <input type="checkbox" id="cek3" onchange="cekSemua()" style="width:16px;height:16px;" />
              <span style="font-size:0.88rem;color:var(--text);">Mengerti bahwa denda <strong style="color:var(--danger);">Rp {{ number_format($denda, 0, ',', '.') }}</strong> harus dibayar ke petugas</span>
            </label>
          @endif
        </div>

        <form action="{{ route('pengembalian.update', $peminjaman) }}" method="POST" id="pengembalianForm">
          @csrf @method('PATCH')
          <div style="display:flex;gap:12px;">
            <button type="submit" id="btnKonfirmasi" disabled
                    style="background:linear-gradient(135deg,#145a32,#1e8449);color:white;padding:13px 32px;border:none;border-radius:8px;font-size:0.95rem;font-weight:700;cursor:not-allowed;opacity:0.5;font-family:var(--font-body);transition:all 0.2s;">
              Konfirmasi Pengembalian
            </button>
            <a href="{{ route('pengembalian.index') }}"
               style="background:#f0f4f8;color:var(--text);padding:13px 22px;border-radius:8px;font-size:0.9rem;font-weight:600;display:flex;align-items:center;">
              Batal
            </a>
          </div>
        </form>

      </div>
    </div>

  </div>
</section>
@endsection

@push('scripts')
<script>
// Aktifkan tombol konfirmasi hanya jika semua checkbox dicentang — DOM manipulation
function cekSemua() {
  const cek1  = document.getElementById('cek1').checked;
  const cek2  = document.getElementById('cek2').checked;
  const cek3  = document.getElementById('cek3'); // null jika tidak ada denda
  const btn   = document.getElementById('btnKonfirmasi');

  const semua = cek3 ? (cek1 && cek2 && cek3.checked) : (cek1 && cek2);

  if (semua) {
    btn.disabled          = false;
    btn.style.opacity     = '1';
    btn.style.cursor      = 'pointer';
  } else {
    btn.disabled          = true;
    btn.style.opacity     = '0.5';
    btn.style.cursor      = 'not-allowed';
  }
}

// Konfirmasi akhir sebelum submit
document.getElementById('pengembalianForm').addEventListener('submit', function(e) {
  const denda = {{ $denda }};
  let pesan   = 'Konfirmasi pengembalian buku "{{ $peminjaman->judul_buku }}"?';
  if (denda > 0) {
    pesan += '\n\nDenda: Rp {{ number_format($denda, 0, ',', '.') }} (dibayar ke petugas).';
  }
  if (!confirm(pesan)) {
    e.preventDefault();
    return;
  }
  const btn       = document.getElementById('btnKonfirmasi');
  btn.textContent = 'Memproses...';
  btn.disabled    = true;
});

// Update denda real-time via AJAX setiap 30 detik
@if($keterlambatan > 0)
async function refreshDenda() {
  try {
    const res  = await fetch('/pengembalian/{{ $peminjaman->id }}/cek', {
      headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
    const json = await res.json();
    const hEl  = document.getElementById('hariTerlambat');
    const dEl  = document.getElementById('jumlahDenda');
    if (hEl) hEl.textContent = json.keterlambatan;
    if (dEl) dEl.textContent = json.denda_format;
  } catch(e) {}
}
setInterval(refreshDenda, 30000);
@endif
</script>
@endpush
