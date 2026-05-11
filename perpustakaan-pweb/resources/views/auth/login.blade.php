<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login — Sistem Informasi Perpustakaan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <style>
    body { padding-top: 0; min-height: 100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #2e86c1 100%); }
    .auth-card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); padding: 48px 44px; width: 100%; max-width: 440px; }
    .auth-logo { text-align:center; margin-bottom: 32px; }
    .auth-logo h1 { font-family: 'Lora', serif; font-size: 1.4rem; color: #1b4f72; margin: 12px 0 4px; }
    .auth-logo p { color: #5d7285; font-size: 0.85rem; margin: 0; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display:block; font-size:0.85rem; font-weight:600; color:#1a252f; margin-bottom:6px; }
    .form-group input { width:100%; padding:11px 14px; border:1.5px solid #d5e0ea; border-radius:8px; font-size:0.9rem; font-family:'Plus Jakarta Sans',sans-serif; outline:none; transition:border 0.2s; box-sizing:border-box; }
    .form-group input:focus { border-color: #2e86c1; }
    .btn-login { width:100%; padding:12px; background: linear-gradient(135deg, #1b4f72, #2e86c1); color:white; border:none; border-radius:8px; font-size:0.95rem; font-weight:600; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; transition:opacity 0.2s; }
    .btn-login:hover { opacity: 0.9; }
    .error-msg { color:#c0392b; font-size:0.8rem; margin-top:4px; }
    .auth-footer { text-align:center; margin-top:24px; font-size:0.88rem; color:#5d7285; }
    .auth-footer a { color:#2e86c1; font-weight:600; }
    .remember-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; font-size:0.85rem; }
    .remember-row label { display:flex; align-items:center; gap:6px; color:#5d7285; cursor:pointer; }
    .alert-error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:8px; padding:12px 16px; margin-bottom:20px; font-size:0.88rem; }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="auth-logo">
      <img src="{{ asset('images/logo.png') }}" width="60" onerror="this.style.display='none'" alt="Logo" />
      <h1>Sistem Informasi Perpustakaan</h1>
      <p>Masuk untuk mengelola data peminjaman</p>
    </div>

    {{-- Session error --}}
    @if (session('status'))
      <div class="alert-error">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="form-group">
        <label for="email">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               placeholder="contoh@email.com" required autofocus />
        @error('email')
          <p class="error-msg">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Masukkan password" required />
        @error('password')
          <p class="error-msg">{{ $message }}</p>
        @enderror
      </div>

      <div class="remember-row">
        <label>
          <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} />
          Ingat saya
        </label>
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}">Lupa password?</a>
        @endif
      </div>

      <button type="submit" class="btn-login">🔐 Masuk</button>
    </form>

    <div class="auth-footer">
      Belum punya akun?
      <a href="{{ route('register') }}">Daftar sekarang</a>
    </div>
  </div>
</body>
</html>
