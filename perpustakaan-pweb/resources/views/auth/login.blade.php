@extends('layouts.guest')
@section('title', 'Login — Perpustakaan UPA UNEJ')

@section('content')
<div class="auth-card" style="background:white;border-radius:16px;padding:40px 44px;width:100%;max-width:440px;box-shadow:0 20px 60px rgba(0,0,0,0.25);">

  <div style="text-align:center;margin-bottom:32px;">
    <h1 style="font-family:var(--font-display);font-size:1.5rem;color:var(--primary-dark);margin:0 0 6px;">Perpustakaan UPA UNEJ</h1>
    <p style="color:var(--text-muted);font-size:0.875rem;margin:0;">Masuk ke akun kamu</p>
  </div>

  {{-- Flash error --}}
  @if(session('status'))
    <div style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:0.875rem;">
      {{ session('status') }}
    </div>
  @endif

  <form method="POST" action="{{ route('login') }}" id="loginForm" style="display:flex;flex-direction:column;gap:18px;">
    @csrf

    {{-- Email --}}
    <div>
      <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
        Email
      </label>
      <input type="email" name="email" value="{{ old('email') }}" required autofocus
             style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
      @error('email')
        <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p>
      @enderror
    </div>

    {{-- Password --}}
    <div>
      <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:6px;">
        Password
      </label>
      <input type="password" name="password" required
             style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('password') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
      @error('password')
        <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p>
      @enderror
    </div>

    {{-- Remember me --}}
    <label style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:var(--text-muted);cursor:pointer;">
      <input type="checkbox" name="remember" style="width:16px;height:16px;" />
      Ingat saya
    </label>

    {{-- Error validasi umum --}}
    @if($errors->any() && !$errors->has('email') && !$errors->has('password'))
      <div style="background:#f8d7da;color:#721c24;border-radius:8px;padding:12px 16px;font-size:0.875rem;">
        @foreach($errors->all() as $err) <p style="margin:0;">{{ $err }}</p> @endforeach
      </div>
    @endif

    <button type="submit" id="loginBtn"
            style="width:100%;padding:13px;background:linear-gradient(135deg,var(--primary),var(--primary-light));color:white;border:none;border-radius:8px;font-size:0.95rem;font-weight:700;cursor:pointer;font-family:var(--font-body);">
      Masuk
    </button>
  </form>

  <p style="text-align:center;margin-top:24px;font-size:0.875rem;color:var(--text-muted);">
    Belum punya akun?
    <a href="{{ route('register') }}" style="color:var(--primary-light);font-weight:600;">Daftar</a>
  </p>

</div>

<script>
// Validasi form login sisi klien
document.getElementById('loginForm').addEventListener('submit', function(e) {
  const email    = this.querySelector('[name="email"]').value.trim();
  const password = this.querySelector('[name="password"]').value;
  const btn      = document.getElementById('loginBtn');

  if (!email || !password) {
    e.preventDefault();
    alert('Email dan password wajib diisi!');
    return;
  }

  // DOM: ubah tombol saat loading
  btn.textContent = 'Memproses...';
  btn.disabled    = true;
});
</script>
@endsection
