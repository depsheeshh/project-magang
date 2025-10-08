<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Buku Tamu Digital')</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

      <!-- CSRF Token (untuk AJAX) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Font Awesome icons -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles2.css') }}" rel="stylesheet" />

    <!-- AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">


    <style>
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
            transition: all 0.3s ease;
            }
            .btn-social:hover {
            background-color: #0d6efd;
            color: #fff;
            transform: translateY(-4px);
            }

    </style>
</head>
<body id="page-top">

    {{-- Navbar --}}
    <x-navbar />

    {{-- Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <x-footer />
    <x-copyright />



    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 1000,   // durasi animasi (ms)
        once: true,       // animasi hanya sekali
        offset: 100       // jarak trigger dari viewport
    });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
