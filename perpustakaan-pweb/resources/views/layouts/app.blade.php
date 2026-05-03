<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Sistem Informasi Perpustakaan')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    @stack('styles')
  </head>
  <body>

    {{-- Flash Session Message --}}
    @if(session('success'))
      <div class="alert alert-success" id="flash-message" style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;padding:12px 22px;margin:0;font-size:0.9rem;display:flex;align-items:center;gap:10px;">
        ✅ {{ session('success') }}
        <button onclick="this.parentElement.style.display='none'" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:1.1rem;color:#155724;">✕</button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-error" id="flash-message" style="background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;padding:12px 22px;margin:0;font-size:0.9rem;display:flex;align-items:center;gap:10px;">
        ❌ {{ session('error') }}
        <button onclick="this.parentElement.style.display='none'" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:1.1rem;color:#721c24;">✕</button>
      </div>
    @endif

    {{-- Navbar partial --}}
    @include('partials.navbar')

    {{-- Main Content Area --}}
      @yield('content')

    {{-- Footer --}}
    <footer>
      <p>© 2026 | Sistem Informasi Perpustakaan | Chelsea Brilliant</p>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
  </body>
</html>
