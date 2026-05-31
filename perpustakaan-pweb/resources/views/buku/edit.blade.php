@extends('layouts.app')
@section('title', 'Edit Buku — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">
  <div class="beranda-hero" style="background:linear-gradient(135deg,#1a5276,#2e86c1);min-height:120px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Edit Buku</div>
      <h2>Edit: {{ $buku->judul }}</h2>
      <p>Perbarui data buku di bawah ini.</p>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:700px;margin:0 auto;">
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#1a5276,#2e86c1);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Edit Buku #{{ $buku->id }}</h3>
      </div>

      <form action="{{ route('buku.update', $buku) }}" method="POST" style="padding:32px;">
        @csrf @method('PUT')

        @if($errors->any())
          <div style="background:#f8d7da;color:#721c24;border-radius:8px;padding:14px 18px;margin-bottom:24px;">
            <strong>Terdapat kesalahan:</strong>
            <ul style="margin:8px 0 0 18px;">
              @foreach($errors->all() as $e) <li style="font-size:0.88rem;">{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Kode Buku *</label>
            <input type="text" name="kode_buku" value="{{ old('kode_buku', $buku->kode_buku) }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" required />
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Kategori *</label>
            <select name="kategori"
                    style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;background:white;" required>
              @foreach(['Teknologi','Sains','Agama','Fiksi','Non-Fiksi','Sejarah'] as $kat)
                <option value="{{ $kat }}" {{ old('kategori', $buku->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
              @endforeach
            </select>
          </div>

          <div style="grid-column:1/-1;">
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Judul Buku *</label>
            <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" required />
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Pengarang *</label>
            <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" required />
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Tahun Terbit *</label>
            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                   min="1900" max="{{ date('Y') }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" required />
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">Stok *</label>
            <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" min="0"
                   style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" required />
          </div>

        </div>

        <div style="display:flex;gap:12px;margin-top:28px;padding-top:20px;border-top:1px solid var(--border);">
          <button type="submit"
                  style="background:linear-gradient(135deg,#1a5276,#2e86c1);color:white;padding:12px 28px;border:none;border-radius:8px;font-size:0.9rem;font-weight:700;cursor:pointer;font-family:var(--font-body);">
            Simpan Perubahan
          </button>
          <a href="{{ route('buku.index') }}"
             style="background:#f0f4f8;color:var(--text);padding:12px 22px;border-radius:8px;font-size:0.9rem;font-weight:600;display:flex;align-items:center;">
            Batal
          </a>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
