@extends('layouts.app')
@section('title', 'Profil — Perpustakaan UPA UNEJ')

@section('content')
<section class="beranda">

  <div class="beranda-hero" style="background:linear-gradient(135deg,#1a2a3a,#2e4057);min-height:130px;">
    <div class="beranda-hero-content">
      <div class="beranda-badge">Profil Saya</div>
      <h2>{{ $user->name }}</h2>
      <p>{{ $user->email }} &bull; <span style="text-transform:capitalize;">{{ $user->role }}</span></p>
      <div class="beranda-cta">
        <a href="{{ route('profile.edit') }}" class="cta-btn cta-primary">Edit Profil</a>
        <a href="{{ route('dashboard') }}" class="cta-btn cta-secondary">Dashboard</a>
      </div>
    </div>
    <div class="beranda-hero-art">
      <div style="width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:3rem;font-weight:800;color:white;">
        {{ strtoupper(substr($user->name, 0, 1)) }}
      </div>
    </div>
  </div>

  <div style="padding:32px 20px;max-width:700px;margin:0 auto;">

    {{-- Info Profil --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
      <div style="background:linear-gradient(135deg,#1a2a3a,#2e4057);padding:16px 28px;">
        <h3 style="color:white;margin:0;">Informasi Akun</h3>
      </div>
      <div style="padding:28px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          @php
            $fields = [
              ['label' => 'Nama Lengkap', 'value' => $user->name],
              ['label' => 'Email',         'value' => $user->email],
              ['label' => 'Role',           'value' => ucfirst($user->role)],
              ['label' => 'Bergabung',      'value' => $user->created_at->format('d F Y')],
            ];
          @endphp
          @foreach($fields as $f)
            <div style="padding:14px 18px;background:#f8fbff;border-radius:8px;border-left:3px solid var(--primary-light);">
              <p style="font-size:0.75rem;color:var(--text-muted);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.5px;">{{ $f['label'] }}</p>
              <p style="font-size:0.92rem;font-weight:600;margin:0;color:var(--text);">{{ $f['value'] }}</p>
            </div>
          @endforeach
        </div>

        <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
          <a href="{{ route('profile.edit') }}"
             style="background:linear-gradient(135deg,#1a2a3a,#2e4057);color:white;padding:11px 24px;border-radius:8px;font-size:0.9rem;font-weight:600;display:inline-block;">
            Edit Profil
          </a>
        </div>
      </div>
    </div>

    {{-- Statistik peminjaman --}}
    <div style="background:white;border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
      <div style="background:linear-gradient(135deg,var(--primary-dark),var(--primary));padding:16px 28px;">
        <h3 style="color:white;margin:0;">Statistik Peminjaman</h3>
      </div>
      <div style="padding:24px;display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
        @php
          $total      = $user->peminjaman()->count();
          $aktif      = $user->peminjaman()->where('status','Dipinjam')->count();
          $kembali    = $user->peminjaman()->where('status','Dikembalikan')->count();
          $terlambat  = $user->peminjaman()->where('status','Terlambat')->count();
        @endphp
        @foreach([
          ['label'=>'Total','nilai'=>$total,   'warna'=>'var(--primary)'],
          ['label'=>'Aktif','nilai'=>$aktif,   'warna'=>'var(--warning)'],
          ['label'=>'Selesai','nilai'=>$kembali,'warna'=>'var(--success)'],
        ] as $s)
          <div style="text-align:center;padding:16px;background:#f8fbff;border-radius:8px;">
            <p style="font-size:1.8rem;font-weight:800;color:{{ $s['warna'] }};margin:0;">{{ $s['nilai'] }}</p>
            <p style="font-size:0.78rem;color:var(--text-muted);margin:4px 0 0;text-transform:uppercase;font-weight:600;">{{ $s['label'] }}</p>
          </div>
        @endforeach
      </div>
    </div>

  </div>
</section>
@endsection
