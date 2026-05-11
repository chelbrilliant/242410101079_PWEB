<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register — Sistem Informasi Perpustakaan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <style>
    body { padding-top: 0; min-height: 100vh; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #2e86c1 100%); }
    .auth-card { background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); padding: 44px; width: 100%; max-width: 440px; }
    .auth-logo { text-align:center; margin-bottom: 28px; }
    .auth-logo h1 { font-family: 'Lora', serif; font-size: 1.4rem; color: #1b4f72; margin: 12px 0 4px; }
    .auth-logo p { color: #5d7285; font-size: 0.85rem; margin: 0; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display:block; font-size:0.85rem; font-weight:600; color:#1a252f; margin-bottom:6px; }
    .form-group input, .form-group select { width:100%; padding:11px 14px; border:1.5px solid #d5e0ea; border-radius:8px; font-size:0.9rem; font-family:'Plus Jakarta Sans',sans-serif; outline:none; transition:border 0.2s; box-sizing:border-box; background:white; }
    .form-group input:focus, .form-group select:focus { border-color: #2e86c1; }
    .btn-register { width:100%; padding:12px; background: linear-gradient(135deg, #27ae60, #2ecc71); color:white; border:none; border-radius:8px; font-size:0.95rem; font-weight:600; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; }
    .btn-register:hover { opacity:0.9; }
    .error-msg { color:#c0392b; font-size:0.8rem; margin-top:4px; }
    .auth-footer { text-align:center; margin-top:20px; font-size:0.88rem; color:#5d7285; }
    .auth-footer a { color:#2e86c1; font-weight:600; }
  </style>
</head>
<body>
  <div class="auth-card">
    <div class="auth-logo">
      <img src="{{ asset('images/logo.png') }}" width="60" onerror="this.style.display='none'" alt="Logo" />
      <h1>Daftar Akun Baru</h1>
      <p>Buat akun untuk mengakses sistem perpustakaan</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="form-group">
        <label for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}"
               placeholder="Nama lengkap kamu" required autofocus />
        @error('name')
          <p class="error-msg">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="email">Alamat Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}"
               placeholder="contoh@email.com" required />
        @error('email')
          <p class="error-msg">{{ $message }}</p>
        @enderror
      </div>

      {{-- Role --}}
      <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role" required>
          <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
          <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
        </select>
        @error('role')
          <p class="error-msg">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="Minimal 8 karakter" required />
        @error('password')
          <p class="error-msg">{{ $message }}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               placeholder="Ulangi password" required />
      </div>

      <button type="submit" class="btn-register">✅ Daftar Sekarang</button>
    </form>

    <div class="auth-footer">
      Sudah punya akun?
      <a href="{{ route('login') }}">Masuk di sini</a>
    </div>
  </div>
</body>
</html>
