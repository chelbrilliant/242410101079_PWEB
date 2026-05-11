@extends('layouts.app')

@section('title', 'Tambah Peminjaman — Sistem Informasi Perpustakaan')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background: linear-gradient(135deg, #145a32 0%, #27ae60 100%); min-height: 130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">➕ Tambah Data</div>
      <h2>Form Peminjaman<br/>Buku Baru</h2>
      <p>Isi formulir di bawah untuk mendaftarkan peminjaman buku baru.</p>
    </div>
    <div class="beranda-hero-art">
      <div class="hero-book-icon">📝</div>
    </div>
  </div>

  <div style="padding: 32px; max-width: 780px; margin: 0 auto;">
    <div style="background: white; border-radius: var(--radius); box-shadow: var(--shadow-md); overflow:hidden;">
      <div style="background: linear-gradient(135deg, #145a32, #27ae60); padding: 16px 28px;">
        <h3 style="color: white; margin:0; font-size: 1rem;">📋 Data Peminjaman Baru</h3>
      </div>

      <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data" style="padding: 32px;">
        @csrf

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
            <input type="text" name="id_anggota" value="{{ old('id_anggota') }}"
                   placeholder="Contoh: ANG-010"
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
            <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}"
                   placeholder="Nama lengkap peminjam"
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
            <input type="text" name="judul_buku" value="{{ old('judul_buku') }}"
                   placeholder="Judul buku yang dipinjam"
                   style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('judul_buku') ? '#c0392b' : 'var(--border)' }}; border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);"
                   required />
            @error('judul_buku')
              <p style="color:#c0392b; font-size:0.78rem; margin-top:4px;">{{ $message }}</p>
            @enderror
          </div>

          {{-- Kode Buku --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">Kode Buku</label>
            <input type="text" name="kode_buku" value="{{ old('kode_buku') }}"
                   placeholder="Contoh: BK-001"
                   style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);" />
          </div>

          {{-- Status --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              Status <span style="color:#c0392b;">*</span>
            </label>
            <select name="status" style="width:100%; padding:10px 14px; border:1.5px solid {{ $errors->has('status') ? '#c0392b' : 'var(--border)' }}; border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body); background:white;" required>
              <option value="">-- Pilih Status --</option>
              @foreach(['Dipinjam','Dikembalikan','Terlambat'] as $s)
                <option value="{{ $s }}" {{ old('status') === $s ? 'selected' : '' }}>{{ $s }}</option>
              @endforeach
            </select>
            @error('status')
              <p style="color:#c0392b; font-size:0.78rem; margin-top:4px;">{{ $message }}</p>
            @enderror
          </div>

          {{-- Tanggal Pinjam --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              Tanggal Pinjam <span style="color:#c0392b;">*</span>
            </label>
            <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);"
                   required />
          </div>

          {{-- Tanggal Kembali --}}
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}"
                   style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body);" />
          </div>

          {{-- Upload Foto --}}
          <div style="grid-column: 1 / -1;">
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">
              📷 Foto Peminjam <span style="color:var(--text-muted); font-weight:400;">(Opsional — jpg/png, maks 2MB)</span>
            </label>
            <input type="file" name="foto" accept="image/jpg,image/jpeg,image/png"
                   id="fotoInput"
                   style="width:100%; padding:10px 14px; border:1.5px dashed {{ $errors->has('foto') ? '#c0392b' : 'var(--border)' }}; border-radius:8px; font-size:0.88rem; outline:none; font-family:var(--font-body); background:#fafafa;" />
            <div id="previewContainer" style="display:none; margin-top:12px;">
              <img id="previewImg" style="width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid var(--border);" />
            </div>
            @error('foto')
              <p style="color:#c0392b; font-size:0.78rem; margin-top:4px;">{{ $message }}</p>
            @enderror
          </div>

          {{-- Keterangan --}}
          <div style="grid-column: 1 / -1;">
            <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:6px;">Keterangan</label>
            <textarea name="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"
                      style="width:100%; padding:10px 14px; border:1.5px solid var(--border); border-radius:8px; font-size:0.9rem; outline:none; font-family:var(--font-body); resize:vertical;">{{ old('keterangan') }}</textarea>
          </div>
        </div>

        {{-- Tombol Aksi --}}
        <div style="display:flex; gap:12px; margin-top:28px; padding-top:20px; border-top: 1px solid var(--border);">
          <button type="submit"
                  style="background: linear-gradient(135deg, #27ae60, #2ecc71); color:white; padding:11px 28px; border:none; border-radius:8px; font-size:0.92rem; font-weight:600; cursor:pointer; animation: pulseGlow 2s infinite;">
            💾 Simpan Peminjaman
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
  // Preview foto sebelum upload
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
  console.log('Halaman Create Peminjaman loaded');
</script>
@endpush
