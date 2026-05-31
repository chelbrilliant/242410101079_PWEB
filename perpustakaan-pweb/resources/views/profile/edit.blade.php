@extends('layouts.app')
@section('title', 'Edit Profil — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background:linear-gradient(135deg,#1a2a3a,#2e4057);min-height:120px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Edit Profil</div>
      <h2>Edit Profil</h2>
      <p>Perbarui informasi akun dan password kamu.</p>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:700px;margin:0 auto;display:flex;flex-direction:column;gap:20px;">

    {{-- Form Edit Nama & Email --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#1a2a3a,#2e4057);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Informasi Profil</h3>
      </div>
      <form action="{{ route('profile.update') }}" method="POST" style="padding:28px;" id="profileForm">
        @csrf @method('PUT')

        @if($errors->any() && !$errors->has('current_password') && !$errors->has('password'))
          <div style="background:#f8d7da;color:#721c24;border-radius:8px;padding:14px 18px;margin-bottom:20px;">
            @foreach($errors->all() as $e) <p style="margin:0;font-size:0.88rem;">{{ $e }}</p> @endforeach
          </div>
        @endif

        <div style="display:flex;flex-direction:column;gap:18px;">
          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Nama Lengkap *
            </label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('name') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
            @error('name') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Email *
            </label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('email') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
            @error('email') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
          </div>

          {{-- Role tidak bisa diubah --}}
          <div style="padding:12px 16px;background:#f0f4f8;border-radius:8px;">
            <p style="font-size:0.78rem;color:var(--text-muted);margin:0 0 2px;">Role (tidak bisa diubah)</p>
            <p style="font-size:0.9rem;font-weight:600;margin:0;color:var(--text);">{{ ucfirst($user->role) }}</p>
          </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
          <button type="submit" id="saveBtn"
                  style="background:linear-gradient(135deg,#1a2a3a,#2e4057);color:white;padding:11px 28px;border:none;border-radius:8px;font-size:0.9rem;font-weight:700;cursor:pointer;font-family:var(--font-body);">
            Simpan Perubahan
          </button>
          <a href="{{ route('profile.show') }}"
             style="background:#f0f4f8;color:var(--text);padding:11px 20px;border-radius:8px;font-size:0.9rem;font-weight:600;display:flex;align-items:center;">
            Batal
          </a>
        </div>
      </form>
    </div>

    {{-- Form Ganti Password --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,#4a235a,#7d3c98);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Ganti Password</h3>
      </div>
      <form action="{{ route('profile.password') }}" method="POST" style="padding:28px;" id="passwordForm">
        @csrf @method('PUT')

        @if($errors->has('current_password') || $errors->has('password'))
          <div style="background:#f8d7da;color:#721c24;border-radius:8px;padding:14px 18px;margin-bottom:20px;">
            @foreach($errors->all() as $e) <p style="margin:0;font-size:0.88rem;">{{ $e }}</p> @endforeach
          </div>
        @endif

        <div style="display:flex;flex-direction:column;gap:18px;">
          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Password Saat Ini *
            </label>
            <input type="password" name="current_password" required
                   style="width:100%;padding:11px 14px;border:1.5px solid {{ $errors->has('current_password') ? 'var(--danger)' : 'var(--border)' }};border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;" />
            @error('current_password') <p style="color:var(--danger);font-size:0.78rem;margin-top:4px;">{{ $message }}</p> @enderror
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Password Baru *
            </label>
            <input type="password" name="password" id="pwBaru" required
                   style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
                   oninput="cekPassword()" />
          </div>

          <div>
            <label style="display:block;font-size:0.82rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:6px;">
              Konfirmasi Password Baru *
            </label>
            <input type="password" name="password_confirmation" id="pwKonfirmasi" required
                   style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:0.9rem;font-family:var(--font-body);outline:none;"
                   oninput="cekPassword()" />
            <p id="pwMsg" style="font-size:0.78rem;margin-top:4px;display:none;"></p>
          </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
          <button type="submit" id="pwBtn"
                  style="background:linear-gradient(135deg,#4a235a,#7d3c98);color:white;padding:11px 28px;border:none;border-radius:8px;font-size:0.9rem;font-weight:700;cursor:pointer;font-family:var(--font-body);">
            Ganti Password
          </button>
        </div>
      </form>
    </div>

  </div>
</section>
@endsection

@push('scripts')
<script>
// Cek konfirmasi password real-time — DOM manipulation
function cekPassword() {
  const pw1  = document.getElementById('pwBaru').value;
  const pw2  = document.getElementById('pwKonfirmasi').value;
  const msg  = document.getElementById('pwMsg');
  const conf = document.getElementById('pwKonfirmasi');

  if (!pw2) { msg.style.display = 'none'; return; }

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

// Validasi sebelum submit ganti password
document.getElementById('passwordForm').addEventListener('submit', function(e) {
  const pw1 = document.getElementById('pwBaru').value;
  const pw2 = document.getElementById('pwKonfirmasi').value;
  if (pw1 !== pw2) {
    e.preventDefault();
    alert('Konfirmasi password tidak cocok!');
    return;
  }
  if (pw1.length < 8) {
    e.preventDefault();
    alert('Password baru minimal 8 karakter!');
  }
});
</script>
@endpush
