<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Sistem Informasi Perpustakaan UPA UNEJ')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body style="padding-top: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0f2e42 0%, #1b4f72 60%, #2e86c1 100%);">
    @yield('content')
  </body>
</html>
