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

  <style>
    /* 🌅 Background gradient */
    body {
      background: linear-gradient(135deg, #eef2ff, #fefce8);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: "Poppins", sans-serif;
    }

    /* ✨ Navbar */
    .navbar {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .navbar-brand {
      font-weight: 700;
      font-size: 1.25rem;
      color: #1e40af !important;
      letter-spacing: 0.3px;
    }
    .navbar-brand i {
      color: #2563eb;
      margin-right: 8px;
    }

    /* 💫 Main container */
    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    .card-guest {
      width: 100%;
      max-width: 600px;
      background: #fff;
      border: none;
      border-radius: 16px;
      box-shadow: 0 6px 24px rgba(0,0,0,0.08);
      overflow: hidden;
      animation: fadeIn 0.6s ease;
    }

    .card-header {
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: #fff;
      text-align: center;
      padding: 25px 15px;
    }
    .card-header h4 {
      margin: 0;
      font-weight: 600;
      letter-spacing: 0.4px;
    }

    .card-body {
      padding: 30px;
    }

    .form-label {
      font-weight: 600;
      color: #1e293b;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #cbd5e1;
      transition: all 0.2s ease;
    }
    .form-control:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.2);
    }

    .btn-primary {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
      border: none;
      border-radius: 10px;
      padding: 10px 18px;
      font-weight: 600;
      transition: 0.3s ease;
    }
    .btn-primary:hover {
      background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
      transform: translateY(-2px);
    }

    .btn-secondary {
      border-radius: 10px;
      font-weight: 500;
    }

    /* 🌟 Footer */
    footer {
      text-align: center;
      padding: 15px;
      font-size: 13px;
      color: #475569;
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(8px);
      box-shadow: 0 -2px 8px rgba(0,0,0,0.03);
    }

    /* ✨ Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* 💖 Success / thanks page */
    .thanks-wrapper {
      text-align: center;
      padding: 60px 20px;
    }
    .thanks-icon {
      font-size: 60px;
      color: #16a34a;
      margin-bottom: 20px;
    }
    .thanks-title {
      font-size: 24px;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 10px;
    }
    .thanks-text {
      color: #475569;
      font-size: 15px;
      max-width: 400px;
      margin: 0 auto;
    }
  </style>

  @stack('styles')
</head>
<body>

  {{-- Navbar --}}
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="{{ url('/') }}">
        <i class="fas fa-book"></i> Buku Tamu Digital
      </a>
    </div>
  </nav>

  {{-- Main --}}
  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer>
    &copy; {{ date('Y') }} Buku Tamu Digital — Dinas Kominfo Kota Cirebon
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
