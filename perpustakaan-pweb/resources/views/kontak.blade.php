@extends('layouts.app')

@section('title', 'Kontak — Sistem Informasi Perpustakaan')

@section('content')

    <section id="kontak" class="beranda">

      <div class="beranda-hero" style="background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #6c3483 100%);">
        <div class="beranda-hero-content">
          <div class="beranda-badge">📬 Kontak Kami</div>
          <h2>Hubungi<br/>Perpustakaan</h2>
          <p>Punya pertanyaan, saran, atau butuh bantuan? Jangan ragu untuk menghubungi kami melalui form di bawah ini.</p>
          <div class="beranda-cta">
            <a href="{{ route('beranda') }}" class="cta-btn cta-primary">🏠 Kembali ke Beranda</a>
            <a href="{{ route('tentang') }}" class="cta-btn cta-secondary">ℹ️ Tentang Kami</a>
          </div>
        </div>
        <div class="beranda-hero-art">
          <div class="hero-book-icon">📬</div>
        </div>
      </div>

      {{-- Info Kontak --}}
      <div class="beranda-fitur">
        <div class="fitur-card">
          <div class="fitur-icon">📍</div>
          <h3>Alamat</h3>
          <p>Perpustakaan Universitas Jember<br>Jl. Kalimantan No.37, Jember<br>Jawa Timur 68121</p>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">📞</div>
          <h3>Telepon & Email</h3>
          <p>Telp: (0331) 330224<br>Email: perpustakaan@unej.ac.id<br>Jam: Senin–Jumat, 08.00–16.00</p>
        </div>
        <div class="fitur-card">
          <div class="fitur-icon">👩‍💻</div>
          <h3>Pengembang</h3>
          <p><strong>Chelsea Brilliant Syah Putra</strong><br>NIM: 242410101079<br>Kelas A — Pemrograman Website</p>
        </div>
      </div>

      {{-- Form Kontak --}}
      <div style="background:#fff; border-radius:12px; border:1px solid #d5e0ea; box-shadow:0 2px 8px rgba(27,79,114,0.08); overflow:hidden; margin-top:16px;">
        <h2 style="font-family:'Lora',serif; font-size:1.05rem; color:#fff; padding:16px 22px; margin:0; background:linear-gradient(135deg,#1b4f72,#6c3483); border-bottom:3px solid #9b59b6;">
          💬 Kirim Pesan
        </h2>
        <div style="padding:28px 24px;">
          <div class="form-grid" id="form-kontak">
            <div class="form-col">
              <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" id="kontak-nama" placeholder="Masukkan nama lengkap Anda">
              </div>
              <div class="form-group">
                <label>Email:</label>
                <input type="email" id="kontak-email" placeholder="contoh@email.com">
              </div>
              <div class="form-group">
                <label>Subjek:</label>
                <input type="text" id="kontak-subjek" placeholder="Subjek pesan">
              </div>
            </div>
            <div class="form-col">
              <div class="form-group">
                <label>Pesan:</label>
                <textarea id="kontak-pesan" rows="5" placeholder="Tuliskan pesan Anda di sini..."></textarea>
              </div>
            </div>
            <div class="form-actions">
              <button type="button" onclick="document.getElementById('kontak-nama').value='';document.getElementById('kontak-email').value='';document.getElementById('kontak-subjek').value='';document.getElementById('kontak-pesan').value='';">Batal</button>
              <button type="button" onclick="alert('Pesan terkirim! Kami akan segera menghubungi Anda.')">Kirim Pesan</button>
            </div>
          </div>
        </div>
      </div>

    </section>

@endsection

@push('scripts')
<script>
console.log('Halaman Kontak loaded');
</script>
@endpush
