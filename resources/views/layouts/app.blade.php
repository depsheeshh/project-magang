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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
