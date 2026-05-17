@extends('layouts.app')

@section('title', 'Statistik Kunjungan — Sistem Informasi Perpustakaan')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background: linear-gradient(135deg, #0f3460 0%, #16213e 100%); min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">📊 Statistik</div>
      <h2>Kunjungan<br/>Halaman</h2>
      <p>Halaman ini mencatat berapa kali kamu mengunjunginya menggunakan Laravel Session.</p>
    </div>
    <div class="beranda-hero-art"><div class="hero-book-icon">📈</div></div>
  </div>

  <div style="padding:32px; max-width:700px; margin:0 auto;">

    <div style="background:white; border-radius:var(--radius); box-shadow:var(--shadow-md); overflow:hidden; margin-bottom:24px;">
      <div style="background:linear-gradient(135deg,#0f3460,#16213e); padding:16px 28px;">
        <h3 style="color:white; margin:0; font-size:1rem;">📊 Data Kunjungan (Session Laravel)</h3>
      </div>

      <div style="padding:32px;">
        {{-- Grid 3 info --}}
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:28px;">

          {{-- Jumlah Kunjungan --}}
          <div style="text-align:center; padding:24px 16px; background:linear-gradient(135deg,#0f3460,#1a4a8a); border-radius:12px; color:white;">
            <div style="font-size:3rem; font-weight:800; line-height:1;">{{ $data['jumlah'] }}</div>
            <div style="font-size:0.82rem; opacity:0.8; margin-top:6px; text-transform:uppercase; letter-spacing:1px;">Total Kunjungan</div>
          </div>

          {{-- Kunjungan Pertama --}}
          <div style="text-align:center; padding:24px 16px; background:linear-gradient(135deg,#145a32,#27ae60); border-radius:12px; color:white;">
            <div style="font-size:1.1rem; font-weight:700; line-height:1.4;">{{ $data['pertama'] }}</div>
            <div style="font-size:0.82rem; opacity:0.8; margin-top:6px; text-transform:uppercase; letter-spacing:1px;">Kunjungan Pertama</div>
          </div>

          {{-- Kunjungan Terakhir --}}
          <div style="text-align:center; padding:24px 16px; background:linear-gradient(135deg,#7d3c98,#a569bd); border-radius:12px; color:white;">
            <div style="font-size:1.1rem; font-weight:700; line-height:1.4;">{{ $data['terakhir'] }}</div>
            <div style="font-size:0.82rem; opacity:0.8; margin-top:6px; text-transform:uppercase; letter-spacing:1px;">Kunjungan Terakhir</div>
          </div>
        </div>

        {{-- Penjelasan session --}}
        <div style="background:#f0f4f8; border-radius:8px; padding:16px 20px; margin-bottom:24px; font-size:0.88rem; color:var(--text-muted); border-left:3px solid var(--primary);">
          <strong>Cara kerja:</strong> Setiap kali halaman ini dibuka, Laravel Session menyimpan
          <code>kunjungan_jumlah</code>, <code>kunjungan_pertama</code>, dan
          <code>kunjungan_terakhir</code>. Data ini bertahan selama sesi browser aktif.
        </div>

        {{-- Tombol Reset via AJAX — SOAL 4 --}}
        <button onclick="resetKunjungan()"
                id="btnReset"
                style="background:linear-gradient(135deg,#c0392b,#e74c3c); color:white; padding:12px 28px; border:none; border-radius:8px; font-size:0.92rem; font-weight:600; cursor:pointer; width:100%;">
          🔄 Reset Hitungan Kunjungan
        </button>
        <div id="resetStatus" style="display:none; margin-top:10px; padding:10px 16px; background:#d4edda; color:#155724; border-radius:8px; font-size:0.88rem; text-align:center;">
          ✅ Hitungan berhasil direset! Halaman akan diperbarui...
        </div>
      </div>
    </div>

    {{-- Info session keys --}}
    <div style="background:white; border-radius:var(--radius); box-shadow:var(--shadow-sm); padding:20px 24px; font-size:0.85rem;">
      <p style="font-weight:600; color:var(--primary); margin:0 0 10px;">🔑 Session Keys yang digunakan:</p>
      <div style="display:flex; flex-direction:column; gap:6px;">
        @foreach(['kunjungan_jumlah' => $data['jumlah'], 'kunjungan_pertama' => $data['pertama'], 'kunjungan_terakhir' => $data['terakhir']] as $key => $val)
          <div style="display:flex; gap:12px; padding:8px 12px; background:#f8f9fa; border-radius:6px;">
            <code style="color:var(--primary); font-size:0.82rem;">{{ $key }}</code>
            <span style="color:var(--text-muted);">→</span>
            <span style="color:var(--text);">{{ $val }}</span>
          </div>
        @endforeach
      </div>
    </div>

  </div>
</section>
@endsection

@push('scripts')
<script>
  // SOAL 4 — Reset kunjungan via AJAX (tidak reload halaman)
  async function resetKunjungan() {
    if (!confirm('Reset semua data kunjungan dari session?')) return;

    const btn = document.getElementById('btnReset');
    btn.textContent = '⏳ Mereset...';
    btn.disabled = true;

    try {
      const response = await fetch('{{ route("kunjungan.reset") }}', {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        }
      });

      const json = await response.json();

      // Tampilkan status berhasil
      document.getElementById('resetStatus').style.display = 'block';

      // Update tampilan angka kunjungan tanpa reload
      setTimeout(() => {
        window.location.reload();
      }, 1500);

    } catch (err) {
      btn.textContent = '🔄 Reset Hitungan Kunjungan';
      btn.disabled = false;
      alert('Gagal reset: ' + err.message);
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    console.log('Halaman Kunjungan loaded — kunjungan ke-{{ $data["jumlah"] }}');
  });
</script>
@endpush
