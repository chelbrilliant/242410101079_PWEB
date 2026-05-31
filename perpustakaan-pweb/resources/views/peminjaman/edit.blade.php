@extends('layouts.app')
@section('title', 'Edit Peminjaman #{{ $peminjaman->id }} — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">
  <div class="beranda-hero" style="background:linear-gradient(135deg,#4a235a,#7d3c98);min-height:120px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Edit Peminjaman</div>
      <h2>Edit Peminjaman #{{ $peminjaman->id }}</h2>
      <p>Perbarui data peminjaman buku di bawah ini.</p>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:720px;margin:0 auto;">
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#4a235a,#7d3c98);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Edit Peminjaman</h3>
      </div>

      <form action="{{ route('peminjaman.update', $peminjaman) }}" method="POST" enctype="multipart/form-data" style="padding:32px;" id="editForm">
        @csrf @method('PUT')

        @if($errors->any())
          <div style="background:#f8d7da;color:#721c24;border-radius:8px;padding:14px 18px;margin-bottom:24px;">
            <strong>Terdapat kesalahan:</strong>
            <ul style="margin:8px 0 0 18px;">
              @foreach($errors->all() as $e) <li style="font-size:0.88rem;">{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        {{-- Info peminjam --}}
        <div style="background:#f0f4f8;border-radius:8px;padding:16px 20px;margin-bottom:24px;">
          <p style="font-size:0.78rem;color:var(--text-muted);margin:0 0 4px;">Peminjam</p>
          <p style="font-size:1rem;font-weight:600;color:var(--text);margin:0;">{{ $peminjaman->nama_peminjam }}</p>
        </div>

        {{-- Pilih Buku --}}
        <div style="margin-bottom:24px;">
          <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">
            Pilih Buku <span style="color:var(--danger);">*</span>
          </label>
          <div style="position:relative;margin-bottom:12px;">
            <input type="text" id="bukuSearch"
                   value="{{ $peminjaman->judul_buku }}"
                   placeholder="Ketik judul, kode, atau pengarang..."
                   style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
                   oninput="cariBuku(this.value)" autocomplete="off" />
            <div id="bukuDropdown"
                 style="display:none;position:absolute;top:100%;left:0;right:0;background:white;border:1.5px solid var(--border);border-radius:0 0 8px 8px;box-shadow:var(--shadow-md);z-index:100;max-height:220px;overflow-y:auto;">
            </div>
          </div>
          <input type="hidden" name="buku_id" id="bukuId" value="{{ $peminjaman->buku_id }}" />
          <div id="bukuTerpilih" style="background:#f0f9f4;border:1.5px solid var(--success);border-radius:8px;padding:14px 18px;">
            <p style="font-size:0.78rem;color:var(--success);font-weight:600;margin:0 0 4px;">Buku Dipilih:</p>
            <p id="bukuTerpilihNama" style="font-weight:700;margin:0;">{{ $peminjaman->judul_buku }}</p>
            <p id="bukuTerpilihInfo" style="font-size:0.82rem;color:var(--text-muted);margin:2px 0 0;">{{ $peminjaman->kode_buku }}</p>
          </div>
          @error('buku_id') <p style="color:var(--danger);font-size:0.78rem;margin-top:6px;">{{ $message }}</p> @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Tanggal Pinjam *
            </label>
            <input type="date" name="tanggal_pinjam" id="tglPinjam"
                   value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam?->format('Y-m-d')) }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
                   onchange="updateMinKembali()" required />
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Tanggal Kembali *
            </label>
            <input type="date" name="tanggal_kembali" id="tglKembali"
                   value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali?->format('Y-m-d')) }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" required />
          </div>

          {{-- Admin bisa ubah status --}}
          @if(auth()->user()->isAdmin())
          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Status
            </label>
            <select name="status"
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;background:white;">
              @foreach(['Dipinjam','Terlambat','Dikembalikan'] as $s)
                <option value="{{ $s }}" {{ $peminjaman->status === $s ? 'selected' : '' }}>{{ $s }}</option>
              @endforeach
            </select>
          </div>
          @endif

          <div style="{{ auth()->user()->isAdmin() ? '' : 'grid-column:1/-1;' }}">
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Keterangan (opsional)
            </label>
            <textarea name="keterangan" rows="3"
                      style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;resize:vertical;">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
          </div>

          <div style="grid-column:1/-1;">
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Foto (opsional — kosongkan jika tidak diubah)
            </label>
            @if($peminjaman->foto)
              <div style="margin-bottom:8px;">
                <img src="{{ asset('storage/' . $peminjaman->foto) }}"
                     style="width:80px;height:80px;border-radius:8px;object-fit:cover;border:2px solid var(--border);" />
              </div>
            @endif
            <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
          </div>

        </div>

        <div style="display:flex;gap:12px;margin-top:28px;padding-top:20px;border-top:1px solid var(--border);">
          <button type="submit" id="submitBtn"
                  style="background:linear-gradient(135deg,#4a235a,#7d3c98);color:white;padding:12px 28px;border:none;border-radius:8px;font-size:0.9rem;font-weight:700;cursor:pointer;font-family:var(--font-body);">
            Simpan Perubahan
          </button>
          <a href="{{ route('peminjaman.show', $peminjaman) }}"
             style="background:#f0f4f8;color:var(--text);padding:12px 22px;border-radius:8px;font-size:0.9rem;font-weight:600;display:flex;align-items:center;">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
let bukuTimeout = null;

function cariBuku(keyword) {
  clearTimeout(bukuTimeout);
  const dropdown = document.getElementById('bukuDropdown');
  if (!keyword.trim()) { dropdown.style.display = 'none'; return; }

  bukuTimeout = setTimeout(async () => {
    try {
      const res  = await fetch(`/buku-cari?keyword=${encodeURIComponent(keyword)}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
      });
      const json = await res.json();

      if (!json.data.length) {
        dropdown.innerHTML    = `<div style="padding:14px 16px;color:var(--text-muted);font-size:0.85rem;">📭 Tidak ada buku tersedia</div>`;
        dropdown.style.display = 'block';
        return;
      }

      dropdown.innerHTML = json.data.map(b => `
        <div onclick="pilihBuku(${b.id},'${b.judul.replace(/'/g,"\\'")}','${b.kode_buku}',${b.stok})"
             style="padding:12px 16px;cursor:pointer;border-bottom:1px solid var(--border);"
             onmouseover="this.style.background='#f0f4f8'" onmouseout="this.style.background='white'">
          <p style="font-weight:600;margin:0;font-size:0.9rem;">${b.judul}</p>
          <p style="font-size:0.78rem;color:var(--text-muted);margin:2px 0 0;">${b.kode_buku} · Stok: <strong style="color:var(--success)">${b.stok}</strong></p>
        </div>`).join('');
      dropdown.style.display = 'block';
    } catch(e) {}
  }, 350);
}

function pilihBuku(id, judul, kode, stok) {
  document.getElementById('bukuId').value               = id;
  document.getElementById('bukuSearch').value           = judul;
  document.getElementById('bukuDropdown').style.display = 'none';
  document.getElementById('bukuTerpilihNama').textContent = judul;
  document.getElementById('bukuTerpilihInfo').textContent = `${kode} — Stok: ${stok}`;
}

document.addEventListener('click', e => {
  if (!e.target.closest('#bukuSearch') && !e.target.closest('#bukuDropdown')) {
    document.getElementById('bukuDropdown').style.display = 'none';
  }
});

function updateMinKembali() {
  const tglPinjam  = document.getElementById('tglPinjam').value;
  const tglKembali = document.getElementById('tglKembali');
  if (tglPinjam) {
    const min = new Date(tglPinjam);
    min.setDate(min.getDate() + 1);
    tglKembali.min = min.toISOString().split('T')[0];
  }
}

document.getElementById('editForm').addEventListener('submit', function(e) {
  const bukuId = document.getElementById('bukuId').value;
  const btn    = document.getElementById('submitBtn');
  if (!bukuId) { e.preventDefault(); alert('Pilih buku terlebih dahulu!'); return; }
  btn.textContent = '⏳ Menyimpan...';
  btn.disabled    = true;
});
</script>
@endpush
