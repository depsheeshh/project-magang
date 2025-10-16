<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') | Buku Tamu Digital</title>

  <!-- Bootstrap & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <style>
    /* 🌌 Background gradient */
    body {
      min-height: 100vh;
      background: linear-gradient(180deg, #0f172a 0%, #081a2e 40%, #05101f 75%, #030814 100%);
      font-family: 'Poppins', sans-serif;
      color: #e0e6f1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    /* ✨ Card utama */
    .auth-card {
      background: rgba(13, 25, 48, 0.9);
      border-radius: 20px;
      box-shadow: 0 8px 30px rgba(0, 145, 255, 0.2);
      backdrop-filter: blur(12px);
      padding: 2.5rem;
      width: 100%;
      max-width: 460px;
      animation: fadeInUp 0.6s ease forwards;
    }

    .auth-card:hover {
      box-shadow: 0 12px 35px rgba(0, 180, 255, 0.35);
    }

    /* Header */
    .auth-header {
      text-align: center;
      margin-bottom: 1.8rem;
    }

    .auth-header h3 {
      font-weight: 700;
      color: #ffffff;
      margin-bottom: .25rem;
    }

    .auth-header p {
      color: #a7b8d8;
      font-size: 14px;
      margin: 0;
    }

    /* Icon bulat */
    .auth-icon {
      background: linear-gradient(135deg, #0077ff, #00b4ff);
      color: white;
      border-radius: 50%;
      width: 65px;
      height: 65px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      margin: 0 auto 1rem;
      box-shadow: 0 0 20px rgba(0, 180, 255, 0.4);
    }

    /* Input */
    .form-control {
      background: rgba(10, 20, 40, 0.6);
      border: 1px solid rgba(0, 170, 255, 0.15);
      color: #e8f1ff;
      border-radius: 10px;
      padding: 0.75rem 1rem;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #00bfff;
      box-shadow: 0 0 12px rgba(0, 200, 255, 0.5);
      background: rgba(15, 25, 55, 0.9);
      color: #fff;
    }

    /* Tombol */
    .btn-primary {
      background: linear-gradient(135deg, #00aaff, #0066ff);
      border: none;
      width: 100%;
      border-radius: 10px;
      font-weight: 600;
      padding: 10px 22px;
      box-shadow: 0 0 15px rgba(0, 157, 255, 0.4);
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #00ccff, #0077ff);
      box-shadow: 0 0 25px rgba(0, 180, 255, 0.6);
      transform: translateY(-2px);
    }

    /* Footer link */
    .auth-footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 14px;
      color: #a7b8d8;
    }

    .auth-footer a {
      color: #00bfff;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .auth-footer a:hover {
      color: #00e0ff;
      text-decoration: underline;
    }

        /* Tambah jarak antar elemen form */
    .auth-card form .form-floating,
    .auth-card form .mb-3,
    .auth-card form .d-grid {
    margin-bottom: 1.2rem; /* beri jarak antar input */
    }

    /* Divider antar section (misalnya "atau") */
    .auth-divider {
    text-align: center;
    margin: 1.5rem 0;
    position: relative;
    }

    .auth-divider span {
    background: rgba(13,25,48,0.9);
    padding: 0 10px;
    color: #a7b8d8;
    font-size: 14px;
    }

    .auth-divider::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    height: 1px;
    background: rgba(255,255,255,0.1);
    z-index: -1;
    }


    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  @stack('styles')
</head>
<body>

  <div class="auth-card">
    <div class="auth-header">
      <div class="auth-icon">
        {{-- Default icon, bisa di-override di child --}}
        <i class="bi bi-shield-lock"></i>
      </div>
      <h3>@yield('title')</h3>
      @hasSection('subtitle')
        <p>@yield('subtitle')</p>
      @endif
    </div>

    {{-- Tempat untuk form auth --}}
    @yield('content')

    {{-- Footer link (misalnya link ke login/register) --}}
    <div class="auth-footer">
      @yield('footer-links')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
