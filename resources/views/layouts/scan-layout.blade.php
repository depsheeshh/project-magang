<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Scan QR Code')</title>

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  {{-- AOS Animation --}}
  <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />

  {{-- Custom Styles --}}
  <style>
    /* =============================
       BACKGROUND FUTURISTIC
       ============================= */
    body {
      background: linear-gradient(135deg, #0f172a, #1e293b, #0f172a);
      min-height: 100vh;
      margin: 0;
      overflow-x: hidden;
      color: #e2e8f0;
      position: relative;
      font-family: 'Poppins', sans-serif;
    }

    /* Mesh Bubble Lights */
    .bg-bubbles {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      overflow: hidden;
      z-index: -1;
    }

    .bg-bubbles span {
      position: absolute;
      display: block;
      width: 30px; height: 30px;
      border-radius: 50%;
      background: rgba(99, 102, 241, 0.25);
      animation: float 12s infinite linear;
      bottom: -150px;
    }
    .bg-bubbles span:nth-child(1) { left: 10%; animation-duration: 10s; }
    .bg-bubbles span:nth-child(2) { left: 30%; animation-duration: 14s; width: 40px; height: 40px; }
    .bg-bubbles span:nth-child(3) { left: 50%; animation-duration: 8s; }
    .bg-bubbles span:nth-child(4) { left: 70%; animation-duration: 12s; width: 50px; height: 50px; }
    .bg-bubbles span:nth-child(5) { left: 90%; animation-duration: 16s; }

    @keyframes float {
      0% { transform: translateY(0); opacity: .5; }
      100% { transform: translateY(-900px); opacity: 0; }
    }

    /* =============================
       WRAPPER UTAMA
       ============================= */
    .scan-wrapper {
      max-width: 480px;
      margin: auto;
      padding: 30px 20px;
      margin-top: 60px;
    }

    /* =============================
       CARD GLASS
       ============================= */
    .scan-card {
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border-radius: 20px;
      padding: 25px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.25);
      border: 1px solid rgba(255,255,255,0.12);
      animation: fadeIn .8s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* =============================
       TITLE MODERN
       ============================= */
    .scan-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #fff;
      text-align: center;
      margin-bottom: 10px;
      letter-spacing: .5px;
    }

    .scan-subtitle {
      font-size: 0.9rem;
      text-align: center;
      color: #cbd5e1;
      margin-bottom: 20px;
    }

    /* =============================
       SCAN AREA
       ============================= */
    .scan-area {
      border: 2px dashed rgba(255,255,255,0.25);
      border-radius: 15px;
      padding: 15px;
      text-align: center;
      color: #e2e8f0;
      margin-bottom: 20px;
      transition: .3s ease;
    }
    .scan-area:hover {
      border-color: rgba(99, 102, 241, 0.9);
      box-shadow: 0 0 20px rgba(99,102,241,0.5);
    }

    /* =============================
       FOOTER
       ============================= */
    .scan-footer {
      text-align: center;
      margin-top: 25px;
      font-size: 12px;
      color: #94a3b8;
    }
  </style>

  @stack('styles')
</head>
<body>

  <!-- BACKGROUND EFFECT -->
  <div class="bg-bubbles">
    <span></span><span></span><span></span><span></span><span></span>
  </div>

  <!-- WRAPPER UTAMA -->
  <div class="scan-wrapper">
      @yield('content')
  </div>

  {{-- JS --}}
  <script src="https://unpkg.com/html5-qrcode"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
  <script>
    AOS.init({ duration: 900, once: true });
  </script>

  @stack('scripts')
</body>
</html>
