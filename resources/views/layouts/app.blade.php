<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Buku Tamu Digital: catat kunjungan dengan cepat, aman, dan efisien." />
  <meta name="theme-color" content="#0d6efd" />
  <meta name="color-scheme" content="dark" />
  <link rel="canonical" href="{{ url()->current() }}" />
  <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <title>@yield('title', 'Buku Tamu Digital')</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&family=Lato:wght@400;700&display=swap" rel="stylesheet" />
  <!-- Bootstrap & Custom CSS -->
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
  <link href="{{ asset('css/styles2.css') }}" rel="stylesheet" />
  <!-- AOS -->
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

  <style>
        body {
            background: linear-gradient(180deg, #0f172a 0%, #081a2e 40%, #05101f 75%, #030814 100%);
            color: #e0e8ff;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        /* Glass effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        /* Navbar */
        .navbar {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        .navbar:hover {
            background: rgba(0, 0, 0, 0.6);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            color: #00d4ff !important;
        }
        .nav-link {
            color: #bcd4f6 !important;
            transition: color 0.2s;
        }
        .nav-link:hover {
            color: #00d4ff !important;
        }

        /* Section padding */
        section {
            padding: 60px 0;
        }

        footer {
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
            color: #bcd4f6;
            padding: 40px 0 20px;
        }

        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            padding-top: 15px;
            color: #829ab1;
            font-size: 0.9rem;
        }

        /* Animasi lembut */
        [data-animate] {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease;
        }
        [data-animate].show {
            opacity: 1;
            transform: translateY(0);
        }

        section::before {
            content: "";
            position: absolute;
            top: -60px;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.3), transparent);
            pointer-events: none;
            }

    </style>
</head>

<body id="page-top">

  {{-- Navbar --}}
  <x-navbar />

  {{-- Konten Utama --}}
  <main id="main-content" tabindex="-1">
    @yield('content')
  </main>

  {{-- Footer --}}
  <x-footer />

  <!-- AOS + Bootstrap + GSAP -->
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // AOS init
    AOS.init({ duration: 900, once: true, offset: 120, easing: 'ease-out-cubic' });

    // GSAP animasi hero
    gsap.from(".masthead-heading", { y: -40, opacity: 0, duration: 1, ease: "power3.out" });
    gsap.from(".masthead-subheading", { y: 20, opacity: 0, duration: 1, delay: 0.3 });
    gsap.from(".masthead .btn", { y: 20, opacity: 0, duration: 0.8, delay: 0.7, stagger: 0.15 });
  });
  </script>
   <script>
        // animasi saat scroll
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('show');
            });
        });
        document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
    </script>

  @stack('scripts')
</body>
</html>
