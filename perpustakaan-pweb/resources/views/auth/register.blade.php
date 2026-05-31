@extends('layouts.guest')
@section('title', 'Daftar — Perpustakaan UPA UNEJ')

@section('content')
<div class="auth-card" style="background:white;border-radius:16px;padding:40px 44px;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,0.25);">

  <div style="text-align:center;margin-bottom:28px;">
    <h1 style="font-family:var(--font-display);font-size:1.5rem;color:var(--primary-dark);margin:0 0 6px;">Daftar Akun</h1>
    <p style="color:var(--text-muted);font-size:0.875rem;margin:0;">Buat akun untuk meminjam buku</p>
  </div>

  <form method="POST" action="{{ route('register') }}" id="registerForm" style="display:flex;flex-direction:column;gap:16px;">
    @csrf

    {{-- Nama --}}
    <div>
      <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Nama Lengkap</label>
      <input type="text" name="name" value="{{ old('name') }}" required
             style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('name') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
      @error('name') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
    </div>

    {{-- Email --}}
    <div>
      <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Email</label>
      <input type="email" name="email" value="{{ old('email') }}" required
             style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
      @error('email') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
    </div>

    {{-- Password --}}
    <div>
      <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Password</label>
      <input type="password" name="password" required id="pwInput"
             style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
      @error('password') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
    </div>

    {{-- Konfirmasi Password --}}
    <div>
      <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">Konfirmasi Password</label>
      <input type="password" name="password_confirmation" required id="pwConfirm"
             style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
             oninput="cekPassword()" />
      <p id="pwMsg" style="font-size:0.78rem;margin-top:4px;display:none;"></p>
    </div>

    <button type="submit" id="regBtn"
            style="width:100%;padding:13px;background:linear-gradient(135deg,var(--accent),var(--teal));color:white;border:none;border-radius:8px;font-size:0.95rem;font-weight:700;cursor:pointer;font-family:var(--font-body);">
      Buat Akun
    </button>
  </form>

  <p style="text-align:center;margin-top:20px;font-size:0.875rem;color:var(--text-muted);">
    Sudah punya akun? <a href="{{ route('login') }}" style="color:var(--primary-light);font-weight:600;">Login</a>
  </p>
</div>

<script>
// Validasi real-time konfirmasi password — DOM manipulation
function cekPassword() {
  const pw1 = document.getElementById('pwInput').value;
  const pw2 = document.getElementById('pwConfirm').value;
  const msg = document.getElementById('pwMsg');
  const conf = document.getElementById('pwConfirm');

  if (pw2.length === 0) { msg.style.display = 'none'; return; }

  if (pw1 === pw2) {
    msg.style.display  = 'block';
    msg.style.color    = 'var(--success)';
    msg.textContent    = 'Password cocok';
    conf.style.border  = '1.5px solid var(--success)';
  } else {
    msg.style.display  = 'block';
    msg.style.color    = 'var(--danger)';
    msg.textContent    = 'Password tidak cocok';
    conf.style.border  = '1.5px solid var(--danger)';
  }
}

// Validasi sebelum submit
document.getElementById('registerForm').addEventListener('submit', function(e) {
  const name = this.querySelector('[name="name"]').value.trim();
  const pw1  = document.getElementById('pwInput').value;
  const pw2  = document.getElementById('pwConfirm').value;
  const btn  = document.getElementById('regBtn');

  if (!name) { e.preventDefault(); alert('Nama lengkap wajib diisi!'); return; }
  if (pw1 !== pw2) { e.preventDefault(); alert('Password dan konfirmasi password tidak cocok!'); return; }
  if (pw1.length < 8) { e.preventDefault(); alert('Password minimal 8 karakter!'); return; }

  btn.textContent = 'Membuat akun...';
  btn.disabled    = true;
});
</script>
@endsection
