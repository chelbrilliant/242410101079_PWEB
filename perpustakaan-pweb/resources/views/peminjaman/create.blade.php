@extends('layouts.app')
@section('title', 'Pinjam Buku — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">
  <div class="beranda-hero" style="background:linear-gradient(135deg,#4a235a,#7d3c98);min-height:120px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Form Peminjaman</div>
      <h2>Pinjam Buku</h2>
      <p>Pilih buku yang ingin dipinjam dan tentukan tanggal pengembalian.</p>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:720px;margin:0 auto;">
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#4a235a,#7d3c98);padding:16px 28px;">
        <h3 style="color:white;margin:0;"> Form Peminjaman Buku</h3>
      </div>

      <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data" style="padding:32px;" id="pinjamForm">
        @csrf

        @if($errors->any())
          <div style="background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:8px;padding:14px 18px;margin-bottom:24px;">
            <strong>Terdapat kesalahan:</strong>
            <ul style="margin:8px 0 0 18px;">
              @foreach($errors->all() as $e) <li style="font-size:0.88rem;">{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        {{-- Info peminjam (otomatis dari akun) --}}
        <div style="background:#f0f4f8;border-radius:8px;padding:16px 20px;margin-bottom:24px;">
          <p style="font-size:0.82rem;color:var(--text-muted);margin:0 0 4px;">Peminjam</p>
          <p style="font-size:1rem;font-weight:600;color:var(--text);margin:0;">{{ auth()->user()->name }}</p>
          <p style="font-size:0.82rem;color:var(--text-muted);margin:2px 0 0;">{{ auth()->user()->email }}</p>
        </div>

        {{-- Cari & Pilih Buku dengan AJAX --}}
        <div style="margin-bottom:24px;">
          <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;">
            Pilih Buku <span style="color:var(--danger);">*</span>
          </label>

          {{-- Search input AJAX --}}
          <div style="position:relative;margin-bottom:12px;">
            <input type="text" id="bukuSearch"
                   placeholder="Ketik judul, kode, atau pengarang buku..."
                   style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
                   oninput="cariBuku(this.value)" autocomplete="off" />
            <div id="bukuLoading" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:0.8rem;color:var(--text-muted);">Mencari</div>

            {{-- Dropdown hasil AJAX --}}
            <div id="bukuDropdown"
                 style="display:none;position:absolute;top:100%;left:0;right:0;background:white;border:1.5px solid var(--border);border-radius:0 0 8px 8px;box-shadow:var(--shadow-md);z-index:100;max-height:220px;overflow-y:auto;">
            </div>
          </div>

          {{-- Hidden input untuk buku_id yang dipilih --}}
          <input type="hidden" name="buku_id" id="bukuId" value="{{ old('buku_id', request('buku_id')) }}" />

          {{-- Card buku yang dipilih --}}
          <div id="bukuTerpilih" style="{{ old('buku_id', request('buku_id')) ? '' : 'display:none;' }}background:#f0f9f4;border:1.5px solid var(--success);border-radius:8px;padding:14px 18px;">
            <p style="font-size:0.78rem;color:var(--success);font-weight:600;margin:0 0 4px;">Buku Dipilih:</p>
            <p id="bukuTerpilihNama" style="font-weight:700;margin:0;color:var(--text);">—</p>
            <p id="bukuTerpilihInfo" style="font-size:0.82rem;color:var(--text-muted);margin:2px 0 0;">—</p>
          </div>

          {{-- Tampilkan buku yang dipilih dari old value --}}
          @if(old('buku_id', request('buku_id')))
            @php $bukuPilih = \App\Models\Buku::find(old('buku_id', request('buku_id'))); @endphp
            @if($bukuPilih)
              <script>
                document.addEventListener('DOMContentLoaded', () => {
                  document.getElementById('bukuTerpilihNama').textContent = '{{ $bukuPilih->judul }}';
                  document.getElementById('bukuTerpilihInfo').textContent = '{{ $bukuPilih->kode_buku }} — Stok: {{ $bukuPilih->stok }}';
                  document.getElementById('bukuSearch').value = '{{ $bukuPilih->judul }}';
                });
              </script>
            @endif
          @endif

          @error('buku_id')
            <p style="color:var(--danger);font-size:0.78rem;margin-top:6px;">{{ $message }}</p>
          @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Tanggal Pinjam <span style="color:var(--danger);">*</span>
            </label>
            <input type="date" name="tanggal_pinjam" id="tglPinjam"
                   value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
                   onchange="updateMinKembali()" required />
            @error('tanggal_pinjam') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Tanggal Kembali <span style="color:var(--danger);">*</span>
            </label>
            <input type="date" name="tanggal_kembali" id="tglKembali"
                   value="{{ old('tanggal_kembali') }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" required />
            @error('tanggal_kembali') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
          </div>

          <div style="grid-column:1/-1;">
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Keterangan (opsional)
            </label>
            <textarea name="keterangan" rows="3"
                      style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;resize:vertical;">{{ old('keterangan') }}</textarea>
          </div>

          <div style="grid-column:1/-1;">
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Foto (opsional)
            </label>
            <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
            <p style="font-size:0.75rem;color:var(--text-muted);margin-top:4px;">Format: JPG, JPEG, PNG. Maks 2MB.</p>
          </div>

        </div>

        <div style="display:flex;gap:12px;margin-top:28px;padding-top:20px;border-top:1px solid var(--border);">
          <button type="submit" id="submitBtn"
                  style="background:linear-gradient(135deg,#4a235a,#7d3c98);color:white;padding:12px 28px;border:none;border-radius:8px;font-size:0.9rem;font-weight:700;cursor:pointer;font-family:var(--font-body);">
            Buat Peminjaman
          </button>
          <a href="{{ route('peminjaman.index') }}"
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

// AJAX: cari buku real-time saat ketik
function cariBuku(keyword) {
  clearTimeout(bukuTimeout);
  const dropdown = document.getElementById('bukuDropdown');
  const loading  = document.getElementById('bukuLoading');

  if (!keyword.trim()) {
    dropdown.style.display = 'none';
    document.getElementById('bukuId').value = '';
    document.getElementById('bukuTerpilih').style.display = 'none';
    return;
  }

  bukuTimeout = setTimeout(async () => {
    loading.style.display = 'block';
    try {
      const res  = await fetch(`/buku-cari?keyword=${encodeURIComponent(keyword)}`, { // kirim keyword ke server
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } // request JSON + keamanan Laravel
      });
      const json = await res.json();
      loading.style.display = 'none';

      if (!json.data.length) {
        dropdown.innerHTML    = `<div style="padding:14px 16px;color:var(--text-muted);font-size:0.85rem;">Tidak ada buku tersedia</div>`;
        dropdown.style.display = 'block';
        return;
      }

      // Render dropdown hasil AJAX — DOM manipulation
      dropdown.innerHTML = json.data.map(b => `
        <div onclick="pilihBuku(${b.id}, '${b.judul.replace(/'/g,"\\'")}', '${b.kode_buku}', ${b.stok})"
             style="padding:12px 16px;cursor:pointer;border-bottom:1px solid var(--border);transition:background 0.15s;"
             onmouseover="this.style.background='#f0f4f8'" onmouseout="this.style.background='white'">
          <p style="font-weight:600;margin:0;font-size:0.9rem;">${b.judul}</p>
          <p style="font-size:0.78rem;color:var(--text-muted);margin:2px 0 0;">${b.kode_buku} · ${b.pengarang} · Stok: <strong style="color:var(--success)">${b.stok}</strong></p>
        </div>`).join('');
      dropdown.style.display = 'block';

    } catch (err) {
      loading.style.display = 'none';
    }
  }, 350);
}

// Pilih buku dari dropdown — update DOM
function pilihBuku(id, judul, kode, stok) {
  document.getElementById('bukuId').value             = id;
  document.getElementById('bukuSearch').value         = judul;
  document.getElementById('bukuDropdown').style.display = 'none';
  document.getElementById('bukuTerpilih').style.display = 'block';
  document.getElementById('bukuTerpilihNama').textContent = judul;
  document.getElementById('bukuTerpilihInfo').textContent = `${kode} — Stok tersedia: ${stok}`;
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', e => {
  if (!e.target.closest('#bukuSearch') && !e.target.closest('#bukuDropdown')) {
    document.getElementById('bukuDropdown').style.display = 'none';
  }
});

// Set min tanggal kembali = tanggal pinjam + 1 hari
function updateMinKembali() {
  const tglPinjam  = document.getElementById('tglPinjam').value;
  const tglKembali = document.getElementById('tglKembali');
  if (tglPinjam) {
    const min = new Date(tglPinjam);
    min.setDate(min.getDate() + 1);
    tglKembali.min = min.toISOString().split('T')[0];
    if (tglKembali.value && tglKembali.value <= tglPinjam) {
      tglKembali.value = min.toISOString().split('T')[0];
    }
  }
}

// Validasi form sebelum submit
document.getElementById('pinjamForm').addEventListener('submit', function(e) {
  const bukuId     = document.getElementById('bukuId').value;
  const tglPinjam  = document.getElementById('tglPinjam').value;
  const tglKembali = document.getElementById('tglKembali').value;
  const btn        = document.getElementById('submitBtn');

  if (!bukuId) {
    e.preventDefault();
    alert('Pilih buku yang akan dipinjam terlebih dahulu!');
    document.getElementById('bukuSearch').focus();
    return;
  }
  if (!tglPinjam || !tglKembali) {
    e.preventDefault();
    alert('Tanggal pinjam dan tanggal kembali wajib diisi!');
    return;
  }
  if (tglKembali <= tglPinjam) {
    e.preventDefault();
    alert('Tanggal kembali harus setelah tanggal pinjam!');
    return;
  }

  btn.textContent = 'Memproses...';
  btn.disabled    = true;
});

// Set min tanggal saat load
document.addEventListener('DOMContentLoaded', updateMinKembali);
</script>
@endpush
