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

  <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/favicon.ico') }}" />

  <!-- Fix Modal Z-Index + Dark Mode -->
<style>
  /* Pastikan modal lebih tinggi dari backdrop */
  .modal-backdrop { z-index: 1040 !important; }
  .modal { z-index: 1055 !important; }
  .modal-dialog, .modal-content { z-index: 1060 !important; }

  /* ===================== DARK MODE ===================== */
  body.dark-mode {
    background-color: #1e1e2f;
    color: #f1f1f1;
  }

  /* Navbar */
  body.dark-mode .navbar {
    background-color: #2a2a3d !important;
    color: #f1f1f1;
  }
  body.dark-mode .navbar .nav-link {
    color: #ddd !important;
  }
  body.dark-mode .navbar .nav-link:hover {
    color: #fff !important;
  }

  /* Sidebar */
  body.dark-mode .main-sidebar {
    background-color: #1c1c2b !important;
    color: #f1f1f1;
  }
  body.dark-mode .main-sidebar .sidebar-brand a {
    color: #f1f1f1;
  }
  body.dark-mode .main-sidebar .sidebar-menu li a {
    color: #ddd;
  }
  body.dark-mode .main-sidebar .sidebar-menu li a:hover {
    background-color: #2a2a3d;
    color: #fff;
  }
  body.dark-mode .main-sidebar .sidebar-menu li.active > a {
    background-color: #34344a;
    color: #fff;
  }
  body.dark-mode .main-sidebar .menu-header {
    color: #aaa;
  }

  /* Card */
  body.dark-mode .card {
    background-color: #2a2a3d;
    color: #f1f1f1;
    border: 1px solid #444;
  }
  body.dark-mode .card-header {
    background-color: #34344a;
    border-bottom: 1px solid #444;
    color: #f1f1f1;
  }
  body.dark-mode .card-body {
    color: #f1f1f1;
  }

  /* Footer */
  body.dark-mode .main-footer {
    background-color: #2a2a3d;
    color: #ccc;
    border-top: 1px solid #444;
  }

  /* Table */
  body.dark-mode table {
    color: #f1f1f1;
  }
  body.dark-mode table thead {
    background-color: #34344a;
  }
  body.dark-mode table tbody tr:nth-child(even) {
    background-color: #2a2a3d;
  }
  body.dark-mode table tbody tr:nth-child(odd) {
    background-color: #262636;
  }

  body.dark-mode .dropdown-menu {
  background-color: #2a2a3d;
  color: #f1f1f1;
  border: 1px solid #444;
}
body.dark-mode .dropdown-menu .dropdown-item {
  color: #ddd;
}
body.dark-mode .dropdown-menu .dropdown-item:hover,
body.dark-mode .dropdown-menu .dropdown-item:focus {
  background-color: #34344a;
  color: #fff;
}


  /* Table header */
    body.dark-mode table thead,
    body.dark-mode table th {
    background-color: #34344a;
    color: #f1f1f1;
    }

    /* Section header (judul dashboard, dll.) */
    body.dark-mode .section-header h1,
    body.dark-mode .section-header {
    color: #f1f1f1;
    background-color: #2a2a3d;
    border-bottom: 1px solid #444;
    }

    /* Dark mode overrides */
    body.dark-mode label {
    color: #e0e0e0; /* label jadi terang */
    }

    body.dark-mode .form-control,
    body.dark-mode .form-select {
    background-color: #2b2b2b;
    color: #f1f1f1;
    border: 1px solid #444;
    }

    body.dark-mode .form-control:focus,
    body.dark-mode .form-select:focus {
    background-color: #2b2b2b;
    color: #fff;
    border-color: #0d6efd; /* biru bootstrap */
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    body.dark-mode textarea.form-control {
    background-color: #2b2b2b;
    color: #f1f1f1;
    }

    /* Dark mode modal overrides */
    body.dark-mode .modal-content {
    background-color: #1e1e1e;   /* latar modal gelap */
    color: #f1f1f1;              /* teks default terang */
    }

    body.dark-mode .modal-header,
    body.dark-mode .modal-footer {
    border-color: #333;          /* garis pemisah lebih halus */
    }

    body.dark-mode .modal-title {
    color: #fff;                 /* judul modal putih */
    }

    body.dark-mode label {
    color: #e0e0e0;              /* label lebih terang */
    }

    body.dark-mode .form-control,
    body.dark-mode .form-select {
    background-color: #2b2b2b;   /* input gelap */
    color: #f1f1f1;              /* teks input terang */
    border: 1px solid #444;      /* border abu */
    }

    body.dark-mode .form-control:focus,
    body.dark-mode .form-select:focus {
    background-color: #2b2b2b;
    color: #fff;
    border-color: #0d6efd;       /* biru bootstrap */
    box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    body.dark-mode .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    }

    body.dark-mode .btn-secondary {
    background-color: #444;
    border-color: #444;
    color: #fff;
    }

    /* .card-statistic-1 .card-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    }

    .card-statistic-1 .card-header,
    .card-statistic-1 .card-body {
    display: inline;
    margin: 0;
    padding: 0 5px;
    } */

</style>
<!-- Tempat untuk CSS tambahan dari child view -->
    @stack('style')
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

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
  <script src="{{ asset('admin/assets/modules/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('admin/assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('admin/assets/js/stisla.js') }}"></script>

  <!-- Template JS File -->
  <script src="{{ asset('admin/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('admin/assets/js/custom.js') }}"></script>

  <!-- Dark Mode Script -->
  <script>
    const body = document.body;
    const toggleBtn = document.getElementById('toggle-darkmode');
    const icon = document.getElementById('darkmode-icon');

    function setIcon() {
      if (body.classList.contains('dark-mode')) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
      } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
      }
    }

    // Inisialisasi: cek localStorage atau OS
    if (localStorage.getItem('theme') === 'dark') {
      body.classList.add('dark-mode');
    } else if (localStorage.getItem('theme') === 'light') {
      body.classList.remove('dark-mode');
    } else {
      if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        body.classList.add('dark-mode');
      }
    }
    setIcon();

    // Event toggle
    toggleBtn.addEventListener('click', function(e) {
      e.preventDefault();
      body.classList.toggle('dark-mode');
      if (body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
      } else {
        localStorage.setItem('theme', 'light');
      }
      setIcon();
    });

    // Update otomatis kalau user ubah setting OS (jika belum override)
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      if (!localStorage.getItem('theme')) {
        if (e.matches) {
          body.classList.add('dark-mode');
        } else {
          body.classList.remove('dark-mode');
        }
        setIcon();
      }
    });
  </script>
  {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
