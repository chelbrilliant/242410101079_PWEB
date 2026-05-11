@extends('layouts.app')

@section('title', 'Edit Peminjaman — Sistem Informasi Perpustakaan')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background: linear-gradient(135deg, #1a5276 0%, #2e86c1 100%); min-height: 130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">✏️ Edit Data</div>
      <h2>Edit Peminjaman<br/>{{ $peminjaman->nama_peminjam }}</h2>
      <p>Perbarui data peminjaman buku di bawah ini.</p>
    </div>
    <div class="beranda-hero-art">
      <div class="hero-book-icon">🖊️</div>
    </div>
  </div>

  <div style="padding: 32px; max-width: 780px; margin: 0 auto;">
    <div style="background: white; border-radius: var(--radius); box-shadow: var(--shadow-md); overflow:hidden;">
      <div style="background: linear-gradient(135deg, #1a5276, #2e86c1); padding: 16px 28px; display:flex; justify-content:space-between; align-items:center;">
        <h3 style="color: white; margin:0; font-size: 1rem;">✏️ Edit Data Peminjaman #{{ $peminjaman->id }}</h3>
        <a href="{{ route('peminjaman.show', $peminjaman) }}"
           style="background:rgba(255,255,255,0.2); color:white; padding:6px 14px; border-radius:6px; font-size:0.82rem;">
          👁 Lihat Detail
        </a>
      </div>

      <form action="{{ route('peminjaman.update', $peminjaman) }}" method="POST" enctype="multipart/form-data" style="padding: 32px;">
        @csrf
        @method('PUT')

        {{-- Error messages --}}
        @if ($errors->any())
          <div style="background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:8px;padding:14px 18px;margin-bottom:24px;">
            <strong>⚠️ Terdapat kesalahan:</strong>
            <ul style="margin: 8px 0 0 18px;">
              @foreach ($errors->all() as $error)
                <li style="font-size: 0.88rem;">{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

          {{-- ID Anggota --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              ID Anggota <span style="color:#c0392b;">*</span>
            </label>
            <input type="text" name="id_anggota"
                   value="{{ old('id_anggota', $peminjaman->id_anggota) }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('id_anggota') ? '#c0392b' : 'var(--border)' }}; border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);"
                   required />
            @error('id_anggota')
              <p style="color:#c0392b; font-size:0.78rem; margin-top:4px;">{{ $message }}</p>
            @enderror
          </div>

          {{-- Nama Peminjam --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              Nama Peminjam <span style="color:#c0392b;">*</span>
            </label>
            <input type="text" name="nama_peminjam"
                   value="{{ old('nama_peminjam', $peminjaman->nama_peminjam) }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('nama_peminjam') ? '#c0392b' : 'var(--border)' }}; border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);"
                   required />
            @error('nama_peminjam')
              <p style="color:#c0392b; font-size:0.78rem; margin-top:4px;">{{ $message }}</p>
            @enderror
          </div>

          {{-- Judul Buku --}}
          <div style="grid-column: 1 / -1;">
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              Judul Buku <span style="color:#c0392b;">*</span>
            </label>
            <input type="text" name="judul_buku"
                   value="{{ old('judul_buku', $peminjaman->judul_buku) }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('judul_buku') ? '#c0392b' : 'var(--border)' }}; border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);"
                   required />
          </div>

          {{-- Kode Buku --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">Kode Buku</label>
            <input type="text" name="kode_buku"
                   value="{{ old('kode_buku', $peminjaman->kode_buku) }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);" />
          </div>

          {{-- Status --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              Status <span style="color:#c0392b;">*</span>
            </label>
            <select name="status" style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body); background:white;" required>
              @foreach(['Dipinjam','Dikembalikan','Terlambat'] as $s)
                <option value="{{ $s }}" {{ old('status', $peminjaman->status) === $s ? 'selected' : '' }}>{{ $s }}</option>
              @endforeach
            </select>
          </div>

          {{-- Tanggal Pinjam --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              Tanggal Pinjam <span style="color:#c0392b;">*</span>
            </label>
            <input type="date" name="tanggal_pinjam"
                   value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam?->format('Y-m-d')) }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);"
                   required />
          </div>

          {{-- Tanggal Kembali --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali"
                   value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali?->format('Y-m-d')) }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);" />
          </div>

          {{-- BONUS: Foto saat ini & upload baru --}}
          <div style="grid-column: 1 / -1;">
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:8px;">
              📷 Foto Peminjam
            </label>
            @if($peminjaman->foto)
              <div style="display:flex; align-items:center; gap:14px; margin-bottom:10px; padding:12px 16px; background:#f0f4f8; border-radius:8px;">
                <img src="{{ asset('storage/' . $peminjaman->foto) }}"
                     style="width:60px; height:60px; border-radius:50%; object-fit:cover; border:3px solid var(--border);" />
                <div>
                  <p style="font-size:0.85rem; font-weight:600; margin:0;">Foto saat ini</p>
                  <p style="font-size:0.78rem; color:var(--text-muted); margin:2px 0 0;">Upload foto baru untuk mengganti</p>
                </div>
              </div>
            @endif
            <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png" id="fotoInput"
                   style="width:100%; padding:10px 14px; border:1.5px dashed var(--border); border-radius:8px; font-size:0.88rem; background:#fafafa;" />
            <p style="font-size:0.78rem; color:var(--text-muted); margin-top:4px;">Format: jpg/jpeg/png — Maks 2 MB. Kosongkan jika tidak ingin mengubah foto.</p>
            <div id="previewContainer" style="display:none; margin-top:10px;">
              <img id="previewImg" style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid var(--border);" />
            </div>
          </div>

          {{-- Keterangan --}}
          <div style="grid-column: 1 / -1;">
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">Keterangan</label>
            <textarea name="keterangan" rows="3"
                      style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body); resize:vertical;">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
          </div>
        </div>

        {{-- Tombol Aksi --}}
        <div style="display:flex; gap:12px; margin-top:28px; padding-top:20px; border-top: 1px solid var(--border);">
          <button type="submit"
                  style="background: linear-gradient(135deg, #1a5276, #2e86c1); color:white; padding:11px 28px; border:none; border-radius:8px; font-size:0.92rem; font-weight:600; cursor:pointer;">
            💾 Update Data
          </button>
          <a href="{{ route('peminjaman.index') }}"
             style="background:#f0f4f8; color:var(--text); padding:11px 22px; border-radius:8px; font-size:0.92rem; font-weight:600;">
            ✕ Batal
          </a>
        </div>

      </form>
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
  document.getElementById('fotoInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = (ev) => {
        document.getElementById('previewImg').src = ev.target.result;
        document.getElementById('previewContainer').style.display = 'block';
      };
      reader.readAsDataURL(file);
    }
  });
  console.log('Halaman Edit Peminjaman loaded');
</script>
@endpush
