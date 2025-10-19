<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') - Dashboard Buku Tamu Digital</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('admin/assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/modules/fontawesome/css/all.min.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/css/components.css') }}">
  <!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

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

  .navbar .navbar-badge {
    position: absolute;
    top: 8px;
    right: 6px;
    font-size: 0.75rem;
    padding: 3px 6px;
    border-radius: 999px;
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

    /* 🎨 NOTIFICATION STYLING */
    #notif-list .notif-item {
    display: flex;
    align-items: start;
    gap: 10px;
    padding: 12px 15px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    transition: all 0.3s ease;
    background: transparent;
    }
    /* Default (Light mode) */
    #notif-list .notif-title {
    font-weight: 600;
    font-size: 14px;
    color: #212529;   /* teks gelap agar terbaca di light mode */
    line-height: 1.2;
    }
    #notif-list .notif-item:hover {
    background: rgba(0, 123, 255, 0.1);
    }
    #notif-list .notif-icon {
    flex-shrink: 0;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    }
    #notif-list .notif-content {
    flex: 1;
    }
    #notif-list .notif-content small {
    color: #a3b3cc;
    }
    #notif-list .notif-close {
    color: #bbb;
    cursor: pointer;
    transition: color 0.2s ease;
    }
    #notif-list .notif-close:hover {
    color: #ff4d4f;
    }

    /* Dark mode compatible */
    body.dark-mode #notif-list .notif-item:hover {
    background: rgba(0, 150, 255, 0.15);
    }
    body.dark-mode #notif-list .notif-content small {
    color: #aaa;
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

    #notif-list .dropdown-item {
        white-space: normal;       /* biar teks bisa turun ke bawah */
        word-wrap: break-word;     /* pecah kata panjang */
        max-width: 280px;          /* batasi lebar dropdown item */
        line-height: 1.4;          /* jarak antar baris lebih enak dibaca */
    }
    /* Container item */
    .notif-item {
    padding: 10px 12px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    transition: background 0.2s ease;
    }
    .notif-item:hover {
    background: rgba(255,255,255,0.05);
    cursor: pointer;
    border-radius: 6px;
    }

    /* Ikon bulat */
    .notif-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    }

    /* Konten */
    #notif-list .notif-title {
        font-weight: 600;
        font-size: 14px;
        color: #212529; /* hitam/gelap */
        line-height: 1.2;
        }
    .notif-sub {
    font-size: 12px;
    color: var(--bs-secondary-color, #6c757d);
    }
    .notif-time {
    font-size: 11px;
    color: var(--bs-secondary-color, #6c757d);
    margin-top: 2px;
    }
    #notif-list .notif-sub,
    #notif-list .notif-time {
    color: #6c757d;   /* abu-abu Bootstrap */
    }
    /* Dark mode override */
    body.dark-mode #notif-list .notif-title {
    color: #f8f9fc;   /* putih agar kontras di dark mode */
    }

    body.dark-mode #notif-list .notif-sub,
    body.dark-mode #notif-list .notif-time {
    color: #aaa;      /* abu terang di dark mode */
    }

    /* Status indicator warna */
    #notif-list .status-disetujui {
    color: #28a745;   /* hijau Bootstrap */
    font-weight: 600;
    }
    #notif-list .status-ditolak {
    color: #dc3545;   /* merah Bootstrap */
    font-weight: 600;
    }
    #notif-list .status-menunggu {
    color: #ffc107;   /* kuning Bootstrap */
    font-weight: 600;
    }

    .table-responsive {
    border: 1px solid #dee2e6; /* sama dengan border card */
    border-radius: .25rem;
    overflow-x: auto;
    }
    .table {
    margin-bottom: 0; /* biar tidak ada gap bawah */
    }
    .table td, .table th {
    word-wrap: break-word; /* kalau mau teks panjang turun ke bawah */
    }



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
  <!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- Template JS File -->
  <script src="{{ asset('admin/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('admin/assets/js/custom.js') }}"></script>

  <script>
    toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "4000",
    "extendedTimeOut": "2000",
    "showDuration": "300",
    "hideDuration": "1000",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    };
    </script>

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

  {{-- Notifications --}}
<script>
(function () {
  const roles = @json(Auth::user()->roles->pluck('name'));
  const endpoint = '/notifikasi';
  let renderHandler = null;

  // === RENDER GENERIC (admin/frontliner/pegawai) ===
  function renderCommon(data, color, icon, url = null) {
    const badge = document.getElementById('notif-badge');
    const list = document.getElementById('notif-list');
    if (!badge || !list) return;

    const items = data.items ?? [];
    if (items.length === 0) {
      badge.classList.add('d-none');
      list.innerHTML = `<span class="dropdown-item text-muted text-center py-3">Tidak ada notifikasi</span>`;
      return;
    }

    badge.textContent = items.length;
    badge.classList.remove('d-none');

    list.innerHTML = items.map(item => `
      <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
           data-id="${item.id}" ${url ? `data-url="${url}"` : ''}>
        <div class="notif-icon ${color} text-white rounded-circle d-flex align-items-center justify-content-center me-3"
             style="width:38px;height:38px;">
          <i class="fas ${icon}"></i>
        </div>
        <div class="notif-content flex-fill">
          <div class="notif-title font-weight-bold"> ${item.nama ?? 'Notifikasi'} </div>
          <div class="notif-sub small">
            ${item.instansi ?? ''} ${item.keperluan ? ' • ' + item.keperluan : ''}
          </div>
          <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    `).join('');
  }

  // === RENDER TAMU ===
  function renderTamu(data) {
    const badge = document.getElementById('notif-badge');
    const list = document.getElementById('notif-list');
    if (!badge || !list) return;

    const items = data.items ?? [];
    if (items.length === 0) {
      badge.classList.add('d-none');
      list.innerHTML = `<span class="dropdown-item text-muted text-center py-3">Tidak ada notifikasi</span>`;
      return;
    }

    badge.textContent = items.length;
    badge.classList.remove('d-none');

    list.innerHTML = items.map(item => {
      let icon = 'fa-clock',
          color = 'bg-warning',
          label = 'Menunggu',
          labelClass = 'status-menunggu';

      if (item.event === 'disetujui') {
        icon = 'fa-check-circle';
        color = 'bg-success';
        label = 'Disetujui';
        labelClass = 'status-disetujui';
      }
      if (item.event === 'ditolak') {
        icon = 'fa-times-circle';
        color = 'bg-danger';
        label = 'Ditolak';
        labelClass = 'status-ditolak';
      }

      return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
             data-id="${item.id}" data-url="/tamu/kunjungan/status">
          <div class="notif-icon ${color} text-white rounded-circle d-flex align-items-center justify-content-center me-3"
               style="width:38px;height:38px;">
            <i class="fas ${icon}"></i>
          </div>
          <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Status kunjungan Anda</div>
            <div class="${labelClass} small">${label}</div>
            ${item.alasan ? `<div class="notif-sub small">Alasan: ${item.alasan}</div>` : ''}
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu}</div>
          </div>
          <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
          </button>
        </div>`;
    }).join('');
  }

  // === Role Selector ===
  if (roles.includes('admin')) {
    renderHandler = (data) => renderCommon(data, 'bg-warning', 'fa-info-circle');
  } else if (roles.includes('frontliner')) {
    renderHandler = (data) => renderCommon(data, 'bg-primary', 'fa-user', '/frontliner/kunjungan');
  } else if (roles.includes('pegawai')) {
    renderHandler = (data) => renderCommon(data, 'bg-info', 'fa-user-friends', '/pegawai/kunjungan/notifikasi');
  } else if (roles.includes('tamu')) {
    renderHandler = renderTamu;
  }

  if (!renderHandler) return;

  // === Polling ===
  const poll = () => {
    fetch(endpoint, { credentials: 'same-origin', cache: 'no-store' })
      .then(res => res.json())
      .then(data => renderHandler(data))
      .catch(() => {});
  };
  poll();
  setInterval(poll, 10000);

  // === Delete one ===
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.delete-notif');
    if (!btn) return;
    const id = btn.dataset.id;

    fetch(`/notifikasi/${id}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    })
      .then(res => res.json())
      .then(() => {
        toastr.success('Notifikasi dihapus.');
        poll();
      })
      .catch(() => toastr.error('Gagal menghapus notifikasi.'));
  });

  // === Mark as read + redirect ===
  document.addEventListener('click', function (e) {
    const item = e.target.closest('.notif-item');
    if (!item || e.target.closest('.delete-notif')) return;
    const id = item.dataset.id;
    const url = item.dataset.url;

    fetch(`/notifikasi/${id}/read`, {
      method: 'PATCH',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    }).then(() => {
      if (url) window.location.href = url;
    });
  });

//  // === CLEAR ALL ===
// document.getElementById('clearAllNotif')?.addEventListener('click', function() {
//     // Show confirmation first
//     if (!confirm('Hapus semua notifikasi?')) return;

//     fetch('/notifikasi/clear', {
//         method: 'DELETE',
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//             'Accept': 'application/json'
//         },
//         credentials: 'same-origin'
//     })
//     .then(res => {
//         if (!res.ok) throw new Error('Network response was not ok');
//         return res.json();
//     })
//     .then(data => {
//         if (data.success) {
//             // Clear UI immediately
//             const badge = document.getElementById('notif-badge');
//             const list = document.getElementById('notif-list');

//             if (badge) {
//                 badge.textContent = '';
//                 badge.classList.add('d-none');
//             }

//             if (list) {
//                 list.innerHTML = `
//                     <span class="dropdown-item text-muted text-center py-3">
//                         Tidak ada notifikasi
//                     </span>
//                 `;
//             }

//             toastr.success('Semua notifikasi berhasil dihapus');
//         } else {
//             throw new Error(data.message || 'Gagal menghapus notifikasi');
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         toastr.error('Gagal menghapus notifikasi');
//     });
// });


})();
</script>






  {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
