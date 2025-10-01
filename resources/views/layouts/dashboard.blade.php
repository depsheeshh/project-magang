<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>@yield('title') - Dashboard Buku Tamu Digital</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('admin/assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/modules/fontawesome/css/all.min.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/css/components.css') }}">

  <!-- Fix Modal Z-Index -->
  <style>
    /* Pastikan modal lebih tinggi dari backdrop */
    .modal-backdrop {
      z-index: 1040 !important;
    }

    .modal {
      z-index: 1055 !important;
    }

    .modal-dialog,
    .modal-content {
      z-index: 1060 !important;
    }
  </style>
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      <!-- Navbar -->
      @include('partials.admin.navbar')

      <!-- Sidebar -->
      @include('partials.admin.sidebar')

      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>@yield('page-title')</h1>
          </div>
          <div class="section-body">
            @include('partials.admin.alert')
            @yield('content')
          </div>
        </section>
      </div>

      <!-- Footer -->
      <footer class="main-footer">
        <div class="footer-left">
          &copy; {{ date('Y') }} Buku Tamu Digital
        </div>
        <div class="footer-right"></div>
      </footer>
    </div>
  </div>

  <!-- ================== TEMPAT MODAL ================== -->
  @yield('modals')
  <!-- ================================================= -->

  <!-- General JS Scripts -->
  <script src="{{ asset('admin/assets/modules/jquery.min.js') }}"></script>
  <!-- WAJIB pakai bundle, bukan bootstrap.min.js -->
  <script src="{{ asset('admin/assets/modules/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('admin/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('admin/assets/js/stisla.js') }}"></script>

  <!-- Template JS File -->
  <script src="{{ asset('admin/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('admin/assets/js/custom.js') }}"></script>
</body>
</html>
