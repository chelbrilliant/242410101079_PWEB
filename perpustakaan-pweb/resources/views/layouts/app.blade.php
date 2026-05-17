<!DOCTYPE html>
<html lang="id" id="html-root">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Sistem Informasi Perpustakaan')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

    <script>
      // Helper functions cookie — SOAL 3b
      function setCookie(name, value, days) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
      }
      function getCookie(name) {
        return document.cookie.split('; ').reduce((r, v) => {
          const parts = v.split('=');
          return parts[0] === name ? decodeURIComponent(parts[1]) : r;
        }, null);
      }
      function deleteCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
      }

      // SOAL 3d — Terapkan dark mode dari cookie sebelum render
      (function() {
        const tema = getCookie('tema');
        const root = document.getElementById('html-root');
        if (tema === 'dark') {
          document.documentElement.classList.add('dark');
        } else if (tema === 'system') {
          if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
          }
        }

        // Terapkan ukuran font dari cookie
        const font = getCookie('ukuran_font');
        if (font === 'small')  document.documentElement.style.fontSize = '14px';
        if (font === 'medium') document.documentElement.style.fontSize = '16px';
        if (font === 'large')  document.documentElement.style.fontSize = '18px';
      })();
    </script>

    {{-- Dark mode CSS override --}}
    <style>
      .dark body        { background: #0f172a !important; color: #e2e8f0 !important; }
      .dark header      { background: linear-gradient(135deg, #0a1929 0%, #0f2744 100%) !important; }
      .dark nav         { background: #1e293b !important; }
      .dark footer      { background: #0f172a !important; border-top: 1px solid #334155; color: #94a3b8; }
      .dark .beranda-hero { filter: brightness(0.85); }
      .dark .fitur-card, .dark .bstat-card { background: #1e293b !important; border-color: #334155 !important; color: #e2e8f0 !important; }
      .dark table        { background: #1e293b; color: #e2e8f0; }
      .dark th           { background: #0f172a !important; color: #94a3b8 !important; }
      .dark tr:hover     { background: #263348 !important; }
      .dark .auth-card   { background: #1e293b !important; color: #e2e8f0 !important; }
    </style>

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

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- Main Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer>
      <p>© 2026 | Sistem Informasi Perpustakaan | Chelsea Brilliant</p>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
    @stack('scripts')
  </body>
</html>
