<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Sistem Informasi Perpustakaan UPA UNEJ')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @stack('styles')
  </head>
  <body>

    {{-- Flash Message --}}
    @if(session('success'))
      <div class="flash flash-success" id="flash-msg">
        {{ session('success') }}
        <button onclick="this.parentElement.style.display='none'">✕</button>
      </div>
    @endif
    @if(session('error'))
      <div class="flash flash-error" id="flash-msg">
        {{ session('error') }}
        <button onclick="this.parentElement.style.display='none'">✕</button>
      </div>
    @endif

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Konten Halaman --}}
    @yield('content')

    {{-- Footer --}}
    <footer>
      <p>© 2026 Sistem Informasi Perpustakaan UPA UNEJ &nbsp;|&nbsp; Universitas Jember</p>
      <p>Kelola peminjaman &amp; pengembalian buku dengan mudah</p>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
  </body>
</html>
