@extends('layouts.app')

@section('title', 'Preferensi — Sistem Informasi Perpustakaan')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background: linear-gradient(135deg, #4a235a 0%, #7d3c98 100%); min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">⚙ Pengaturan</div>
      <h2>Preferensi<br/>Tampilan</h2>
      <p>Atur tema dan ukuran font sesuai kenyamananmu.</p>
    </div>
    <div class="beranda-hero-art"><div class="hero-book-icon">🎨</div></div>
  </div>

  <div style="padding:32px; max-width:600px; margin:0 auto;">
    <div style="background:white; border-radius:var(--radius); box-shadow:var(--shadow-md); overflow:hidden;">
      <div style="background:linear-gradient(135deg,#4a235a,#7d3c98); padding:16px 28px;">
        <h3 style="color:white; margin:0; font-size:1rem;">⚙ Form Preferensi</h3>
      </div>

      {{-- Notifikasi simpan berhasil --}}
      <div id="notif" style="display:none; padding:12px 24px; background:#d4edda; color:#155724; font-size:0.88rem; border-bottom:1px solid #c3e6cb;">
        ✅ <span id="notif-msg">Preferensi berhasil disimpan!</span>
      </div>

      <div style="padding:32px;">

        {{-- SOAL 3e — Form pilihan tema dan ukuran font --}}
        <div style="margin-bottom:24px;">
          <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:10px;">
            🎨 Pilihan Tema
          </label>
          <div style="display:flex; gap:12px;">
            @foreach(['light' => ['☀️','Light','Terang'], 'dark' => ['🌙','Dark','Gelap'], 'system' => ['💻','System','Otomatis']] as $val => $info)
              <label style="flex:1; cursor:pointer;">
                <input type="radio" name="tema" value="{{ $val }}"
                       {{ $tema === $val ? 'checked' : '' }}
                       style="display:none;" class="tema-radio" />
                <div class="tema-option {{ $tema === $val ? 'selected' : '' }}"
                     data-value="{{ $val }}"
                     style="border:2px solid {{ $tema === $val ? '#7d3c98' : 'var(--border)' }}; border-radius:10px; padding:14px 10px; text-align:center; transition:all 0.2s; background:{{ $tema === $val ? '#f5eef8' : 'white' }};">
                  <div style="font-size:1.6rem;">{{ $info[0] }}</div>
                  <div style="font-weight:600; font-size:0.85rem; margin-top:4px;">{{ $info[1] }}</div>
                  <div style="font-size:0.75rem; color:var(--text-muted);">{{ $info[2] }}</div>
                </div>
              </label>
            @endforeach
          </div>
        </div>

        <div style="margin-bottom:28px;">
          <label style="display:block; font-size:0.85rem; font-weight:600; color:var(--text); margin-bottom:10px;">
            🔤 Ukuran Font
          </label>
          <div style="display:flex; gap:12px;">
            @foreach(['small' => ['Kecil','14px'], 'medium' => ['Sedang','16px'], 'large' => ['Besar','18px']] as $val => $info)
              <label style="flex:1; cursor:pointer;">
                <input type="radio" name="ukuran_font" value="{{ $val }}"
                       {{ $ukuranFont === $val ? 'checked' : '' }}
                       style="display:none;" class="font-radio" />
                <div class="font-option {{ $ukuranFont === $val ? 'selected' : '' }}"
                     data-value="{{ $val }}"
                     style="border:2px solid {{ $ukuranFont === $val ? '#7d3c98' : 'var(--border)' }}; border-radius:10px; padding:14px 10px; text-align:center; transition:all 0.2s; background:{{ $ukuranFont === $val ? '#f5eef8' : 'white' }};">
                  <div style="font-size:{{ $info[1] }}; font-weight:700;">A</div>
                  <div style="font-size:0.8rem; margin-top:4px; font-weight:600;">{{ $info[0] }}</div>
                  <div style="font-size:0.72rem; color:var(--text-muted);">{{ $info[1] }}</div>
                </div>
              </label>
            @endforeach
          </div>
        </div>

        {{-- SOAL 3f — Simpan via Fetch POST --}}
        <button onclick="simpanPreferensi()"
                style="width:100%; background:linear-gradient(135deg,#4a235a,#7d3c98); color:white; padding:13px; border:none; border-radius:8px; font-size:0.95rem; font-weight:600; cursor:pointer; margin-bottom:10px;">
          💾 Simpan Preferensi
        </button>

        {{-- SOAL 3b deleteCookie — Reset semua preferensi --}}
        <button onclick="resetPreferensi()"
                style="width:100%; background:#f0f4f8; color:var(--text); padding:13px; border:1.5px solid var(--border); border-radius:8px; font-size:0.95rem; font-weight:600; cursor:pointer;">
          🗑 Reset Preferensi (deleteCookie)
        </button>

        <div style="margin-top:16px; padding:14px; background:#f8f9fa; border-radius:8px; font-size:0.82rem; color:var(--text-muted);">
          <strong>Cookie saat ini:</strong><br>
          Tema: <code id="cookie-tema">{{ $tema }}</code> |
          Font: <code id="cookie-font">{{ $ukuranFont }}</code>
        </div>

      </div>
    </div>
  </div>

</section>
@endsection

@push('scripts')
<script>
  // Pilih tema
  document.querySelectorAll('.tema-option').forEach(el => {
    el.addEventListener('click', () => {
      document.querySelectorAll('.tema-option').forEach(o => {
        o.style.border = '2px solid var(--border)';
        o.style.background = 'white';
      });
      el.style.border = '2px solid #7d3c98';
      el.style.background = '#f5eef8';
      el.previousElementSibling
        ? null
        : el.closest('label').querySelector('input').checked = true;
      el.closest('label').querySelector('input').checked = true;
    });
  });

  // Pilih font
  document.querySelectorAll('.font-option').forEach(el => {
    el.addEventListener('click', () => {
      document.querySelectorAll('.font-option').forEach(o => {
        o.style.border = '2px solid var(--border)';
        o.style.background = 'white';
      });
      el.style.border = '2px solid #7d3c98';
      el.style.background = '#f5eef8';
      el.closest('label').querySelector('input').checked = true;
    });
  });

  // SOAL 3b — deleteCookie: hapus semua preferensi
  function resetPreferensi() {
    deleteCookie('tema');
    deleteCookie('ukuran_font');

    // Kembalikan tampilan ke default
    document.documentElement.classList.remove('dark');
    document.documentElement.style.fontSize = '16px';

    // Update label cookie
    document.getElementById('cookie-tema').textContent = 'belum diset';
    document.getElementById('cookie-font').textContent = 'belum diset';

    // Tampilkan notifikasi
    const notif = document.getElementById('notif');
    notif.style.background = '#f8d7da';
    notif.style.color = '#721c24';
    document.getElementById('notif-msg').textContent = 'Preferensi berhasil direset! Cookie dihapus.';
    notif.style.display = 'block';
    setTimeout(() => { notif.style.display = 'none'; notif.style.background='#d4edda'; notif.style.color='#155724'; }, 3000);
  }

  // SOAL 3f — Simpan preferensi via Fetch POST ke Laravel
  async function simpanPreferensi() {
    const tema       = document.querySelector('input[name="tema"]:checked')?.value || 'light';
    const ukuranFont = document.querySelector('input[name="ukuran_font"]:checked')?.value || 'medium';

    try {
      const response = await fetch('{{ route("preferensi.simpan") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json',
        },
        body: JSON.stringify({ tema, ukuran_font: ukuranFont })
      });

      const json = await response.json();

      if (json.status === 'success') {
        // Terapkan langsung ke halaman
        setCookie('tema', tema, 30);
        setCookie('ukuran_font', ukuranFont, 30);

        // Toggle dark mode
        if (tema === 'dark') {
          document.documentElement.classList.add('dark');
        } else if (tema === 'light') {
          document.documentElement.classList.remove('dark');
        } else {
          if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        }

        // Update font size
        const sizes = { small: '14px', medium: '16px', large: '18px' };
        document.documentElement.style.fontSize = sizes[ukuranFont] || '16px';

        // Update label cookie
        document.getElementById('cookie-tema').textContent = json.tema;
        document.getElementById('cookie-font').textContent = json.ukuran_font;

        // Tampilkan notifikasi
        const notif = document.getElementById('notif');
        document.getElementById('notif-msg').textContent = json.message + ' (Tema lama: ' + json.tema_lama + ')';
        notif.style.display = 'block';
        setTimeout(() => notif.style.display = 'none', 3000);
      }
    } catch (err) {
      alert('Gagal menyimpan preferensi: ' + err.message);
    }
  }
</script>
@endpush
