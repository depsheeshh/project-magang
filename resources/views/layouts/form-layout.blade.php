<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Form')</title>

  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  {{-- Custom DKIS Form Styles --}}
  <style>
    body {
      background: radial-gradient(circle at top, #0b1320 0%, #070c16 100%);
      color: #e0e6f1;
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
      padding-top: 40px;
      padding-bottom: 40px;
    }
    .form-wrapper {
      max-width: 960px;
      margin: auto;
      padding: 30px;
    }
    .card {
      border-radius: 24px;
      background: rgba(15, 20, 40, 0.9);
      backdrop-filter: blur(20px);
      box-shadow: 0 0 35px rgba(0, 170, 255, 0.15);
      transition: 0.4s ease;
    }
    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 0 45px rgba(0, 180, 255, 0.3);
    }
    .card-header {
      background: linear-gradient(90deg, #006aff, #00b8ff);
      color: #fff;
      text-align: center;
      border: none;
      padding: 1.2rem 0;
      font-weight: 600;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 20px rgba(0, 132, 255, 0.4);
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="form-wrapper">
    @yield('content')
  </div>

  {{-- Bootstrap 5 JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
