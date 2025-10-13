<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Buku Tamu Digital: catat kunjungan dengan cepat, aman, dan efisien." />
    <meta name="theme-color" content="#0d6efd" />
    <meta name="color-scheme" content="light dark" />
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <title>@yield('title', 'Buku Tamu Digital')</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/favicon.ico') }}" />

      <!-- CSRF Token (untuk AJAX) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles2.css') }}" rel="stylesheet" />

    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="preload" as="image" href="{{ asset('assets/img/portfolio/4300_7_03.png') }}" fetchpriority="high" />


    <style>
        :root {
            --brand: #0d6efd;
            --brand-contrast: #ffffff;
            --navy: #0b2e4f;
            --navy-contrast: #ffffff;
            --bg: #ffffff;
            --text: #1b1f23;
        }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Lato', 'Segoe UI', Roboto, Arial, sans-serif;
            color: var(--text);
            background-color: var(--bg);
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        h1, h2, h3, .navbar-brand { font-family: 'Montserrat', 'Lato', sans-serif; }
        .bg-navy { background-color: var(--navy) !important; color: var(--navy-contrast) !important; }
        .navbar a.nav-link:focus-visible, .navbar .navbar-brand:focus-visible, .dropdown-item:focus-visible, .btn:focus-visible {
            outline: 3px solid var(--brand);
            outline-offset: 2px;
            border-radius: .25rem;
        }
        .skip-link {
            position: absolute;
            left: -9999px;
            top: auto;
            width: 1px;
            height: 1px;
            overflow: hidden;
        }
        .skip-link:focus {
            position: fixed;
            left: 1rem;
            top: 1rem;
            width: auto;
            height: auto;
            padding: .5rem .75rem;
            background: #000;
            color: #fff;
            z-index: 10000;
            border-radius: .25rem;
        }
        img { height: auto; }
        .feature-box {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .feature-box:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .btn-social {
            width: 45px;
            height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        .btn-social:hover {
            background-color: var(--brand);
            color: #fff;
            transform: translateY(-4px);
        }

        .btn-primary {
            transition: all 0.3s ease;
            }
            .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            }
            .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
            }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }
            [data-aos] { opacity: 1 !important; transform: none !important; }
        }
    </style>
</head>
<body id="page-top">

    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    {{-- Navbar --}}
    <x-navbar />

    {{-- Content --}}
    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />
    <x-copyright />



    <!-- AOS JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (!reduceMotion) {
            AOS.init({
                duration: 1000,
                once: true,
                offset: 100
            });
        }
    });
    </script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        if (reduceMotion) return;

        gsap.from(".masthead-heading", {
            y: -50,
            opacity: 0,
            duration: 1,
            ease: "power3.out"
        });

        gsap.from(".masthead-avatar", {
            scale: 0,
            opacity: 0,
            duration: 1,
            delay: 0.5,
            ease: "back.out(1.7)"
        });

        gsap.from(".masthead-subheading", {
            y: 30,
            opacity: 0,
            duration: 1,
            delay: 1,
            ease: "power2.out"
        });

        gsap.from(".masthead .btn", {
            y: 20,
            opacity: 0,
            duration: 0.8,
            delay: 1.5,
            stagger: 0.2
        });
    });
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="{{ asset('js/scripts.js') }}"></script>

    {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
