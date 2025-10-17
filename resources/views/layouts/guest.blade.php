<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Buku Tamu Digital')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Font Awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  @stack('styles')
</head>
<body class="bg-light">

  {{-- Header --}}
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ url('/') }}">
        <i class="fas fa-book"></i> Buku Tamu Digital
      </a>
    </div>
  </nav>

  {{-- Konten utama --}}
  <main class="container py-5">
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="text-center py-3 text-muted border-top">
    &copy; {{ date('Y') }} Buku Tamu Digital
  </footer>

  {{-- Bootstrap 5 JS Bundle --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
