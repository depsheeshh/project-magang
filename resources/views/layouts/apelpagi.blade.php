<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Apel Pagi DKIS</title>

  {{-- Bootstrap 5 CDN --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Font Awesome --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  {{-- Custom Style Modern --}}
  <style>
    body {
      background: linear-gradient(145deg, #eef2f7, #dbe4ee);
      background-attachment: fixed;
      font-family: 'Segoe UI', sans-serif;
      color: #1f2937;
      padding-bottom: 40px;
    }

    /* Navbar Glass */
    .navbar-custom {
      backdrop-filter: blur(10px);
      background: rgba(0, 60, 150, 0.75);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .navbar-brand {
      color: #fff !important;
      font-weight: 700;
      font-size: 20px;
      letter-spacing: 0.5px;
    }

    /* Wrapper */
    .page-wrapper {
      margin-top: 35px;
    }

    /* Card default styling */
    .card-modern {
      border: none;
      border-radius: 14px;
      background: #ffffff;
      box-shadow: 0 4px 18px rgba(0,0,0,0.08);
      transition: 0.25s ease;
    }

    .card-modern:hover {
      box-shadow: 0 8px 26px rgba(0,0,0,0.12);
      transform: translateY(-2px);
    }

    /* Footer */
    footer {
      margin-top: 50px;
      text-align: center;
      font-size: 13px;
      color: #6b7280;
    }

  </style>

  @stack('styles')
</head>

<body>

  {{-- NAV --}}
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
      <a class="navbar-brand" href="#">
        <i class="fa-solid fa-sun mr-2"></i> Apel Pagi DKIS
      </a>
    </div>
  </nav>


  {{-- CONTENT --}}
  <div class="container page-wrapper">
      @yield('content')
  </div>


  {{-- Footer --}}
  <footer>
    &copy; {{ date('Y') }} Dinas Komunikasi, Informatika, dan Statistik – Kota Cirebon
  </footer>


  {{-- Bootstrap JS CDN --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @stack('scripts')

</body>
</html>
