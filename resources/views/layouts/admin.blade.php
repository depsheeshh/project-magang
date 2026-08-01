<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">


  <title>@yield('title') - Dashboard Buku Tamu Digital</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('admin/assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/modules/fontawesome/css/all.min.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/assets/css/components.css') }}">
  <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">


  <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/favicon.ico') }}" />

    <link rel="stylesheet" href="{{ asset('css/style-dark.css') }}">

    <style>
        .dropdown-menu.dropdown-menu-lg {
        width: 100%;
        max-width: 380px;
        max-height: 80vh;
        overflow-y: auto;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .notif-item {
        transition: background 0.2s ease, transform 0.2s ease;
        }
        .notif-item:hover {
        background: rgba(59,130,246,0.08);
        transform: translateX(2px);
        cursor: pointer;
        }

        .notif-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.1rem;
        }
        .notif-title {
        font-weight: 600;
        color: #111827;
        }
        .notif-sub {
        color: #4b5563;
        }
        .notif-time {
        color: #6b7280;
        font-size: 0.75rem;
        }

        mark {
        background-color: yellow;
        padding: 0 2px;
        border-radius: 2px;
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
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="{{ asset('admin/assets/js/stisla.js') }}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=API_KEY&libraries=places&callback=initMap" async defer></script>

  <!-- SweetAlert2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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

  // === Helper Render Per Event ===
  function renderInstansiBaru(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
            data-id="${item.id}" data-url="/admin/instansi">
        <div class="notif-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-building"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Instansi Baru Ditambahkan</div>
            <div class="notif-sub small">${item.nama_instansi || '-'} oleh ${item.user || 'Peserta'}</div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu || ''}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderUserBaru(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
            data-id="${item.id}" data-url="${item.url}">
        <div class="notif-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">User Baru Ditambahkan</div>
            <div class="notif-sub small">
            ${item.nama} (${item.email}) • via ${
                item.source === 'form_tamu' ? 'Form Tamu' :
                item.source === 'google' ? 'Google' : 'Register'
            }
            </div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderSurveyPelayanan(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
            data-id="${item.id}" data-url="/admin/surveys">
        <div class="notif-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-poll"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Survey Pelayanan Baru</div>
            <div class="notif-sub small">${item.judul || 'Survey'} oleh ${item.user || 'Peserta'}</div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu || ''}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderSurveyBaru(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
            data-id="${item.id}" data-url="/admin/surveys">
        <div class="notif-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-comment-dots"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Survey Baru Ditambahkan</div>
            <div class="notif-sub small">${item.judul || 'Survey'} oleh ${item.user || 'Admin/Peserta'}</div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu || ''}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderSurveyRapatBaru(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-3"
            data-id="${item.id}" data-url="/admin/survey-rapat/${item.survey_id}">
        <div class="notif-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-poll-h"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Survey Rapat Baru</div>
            <div class="notif-sub small">${item.judul || 'Survey'} • oleh ${item.user || 'Admin'}</div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderSurveyRapatRespon(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-3"
            data-id="${item.id}" data-url="/admin/survey-rapat/${item.survey_id}">
        <div class="notif-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Respon Survey Rapat</div>
            <div class="notif-sub small">${item.user || 'Peserta'} ${item.instansi ? '• ' + item.instansi : ''}</div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

  function renderRapatUndanganPegawai(item) { return `
          <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
               data-id="${item.id}" data-url="/pegawai/rapat/${item.rapat_id}">
            <div class="notif-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                 style="width:38px;height:38px;">
              <i class="fas fa-handshake"></i>
            </div>
            <div class="notif-content flex-fill">
              <div class="notif-title font-weight-bold">Undangan Rapat Baru</div>
              <div class="notif-sub small">${item.judul || 'Rapat'} • ${(item.waktu || '')}</div>
              <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu_notif || '')}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        `; }
  function renderRapatUndanganTamu(item) { return `
          <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
               data-id="${item.id}" data-url="/tamu/rapat/${item.rapat_id}">
            <div class="notif-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                 style="width:38px;height:38px;">
              <i class="fas fa-handshake"></i>
            </div>
            <div class="notif-content flex-fill">
              <div class="notif-title font-weight-bold">Undangan Rapat Baru</div>
              <div class="notif-sub small">${item.judul || 'Rapat'} • ${(item.waktu || '')}</div>
              <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu_notif || '')}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        `; }
  function renderRapatDibatalkan(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2" data-id="${item.id}">
        <div class="notif-icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-ban"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Undangan Rapat Dibatalkan</div>
            <div class="notif-sub small">${item.judul || 'Rapat'} • ${item.waktu || ''}</div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu_notif || ''}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderKunjunganDisetujui(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2" data-id="${item.id}">
        <div class="notif-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Status kunjungan Anda</div>
            <div class="status-disetujui small">Disetujui</div>
            ${item.alasan ? `<div class="notif-sub small">Alasan: ${item.alasan}</div>` : ''}
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu || ''}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderKunjunganDitolak(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2" data-id="${item.id}">
        <div class="notif-icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Status kunjungan Anda</div>
            <div class="status-ditolak small">Ditolak</div>
            ${item.alasan ? `<div class="notif-sub small">Alasan: ${item.alasan}</div>` : ''}
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu || ''}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderKunjunganMenunggu(item) {
    return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2" data-id="${item.id}">
        <div class="notif-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
            style="width:38px;height:38px;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">Status kunjungan Anda</div>
            <div class="status-menunggu small">Menunggu</div>
            ${item.alasan ? `<div class="notif-sub small">Alasan: ${item.alasan}</div>` : ''}
            <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu || ''}</div>
        </div>
        <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
        </button>
        </div>`;
    }

    function renderTamuBaru(item) {
        return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2" data-id="${item.id}">
            <div class="notif-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:38px;height:38px;">
                <i class="fas fa-user"></i>
            </div>
            <div class="notif-content flex-fill">
                <div class="notif-title font-weight-bold">Tamu Baru Menunggu Persetujuan</div>
                <div class="notif-sub small">${item.nama} • ${item.instansi} • Keperluan: ${item.keperluan}</div>
                <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
                <i class="fas fa-trash"></i>
            </button>
        </div>`;
    }

    function renderTamuBaruPegawai(item) {
        return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2" data-id="${item.id}">
            <div class="notif-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:38px;height:38px;">
                <i class="fas fa-user"></i>
            </div>
            <div class="notif-content flex-fill">
                <div class="notif-title font-weight-bold">Tamu Baru untuk Anda</div>
                <div class="notif-sub small">${item.nama} • ${item.instansi} • Keperluan: ${item.keperluan}</div>
                <div class="notif-time small"><i class="fas fa-clock"></i> ${item.waktu}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
                <i class="fas fa-trash"></i>
            </button>
        </div>`;
    }


    // === Handler Per Role ===
    function renderAdmin(data) {
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
        switch(item.event) {
            case 'instansi_baru': return renderInstansiBaru(item);
            case 'user_baru': return renderUserBaru(item);
            case 'survey_pelayanan': return renderSurveyPelayanan(item);
            case 'survey_baru': return renderSurveyBaru(item);
            case 'survey_rapat_baru': return renderSurveyRapatBaru(item);
            case 'survey_rapat_respon': return renderSurveyRapatRespon(item);
            default: return '';
        }
        }).join('');
    }

    function renderFrontliner(data) {
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
            switch(item.event) {
                case 'tamu_baru':
                    return renderTamuBaru(item);
                case 'disetujui':
                    return renderKunjunganDisetujui(item);
                case 'ditolak':
                    return renderKunjunganDitolak(item);
                default:
                    return '';
            }
        }).join('');
    }


  function renderPegawai(data) {
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
      switch(item.event) {
        case 'tamu_baru': return renderTamuBaruPegawai(item);
        case 'rapat_undangan': return renderRapatUndanganPegawai(item);
        case 'survey_rapat_baru': return renderSurveyRapatBaru(item);
        case 'survey_rapat_respon': return renderSurveyRapatRespon(item);
        default: return '';
      }
    }).join('');
  }

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
      switch(item.event) {
        case 'rapat_undangan': return renderRapatUndanganTamu(item);
        case 'rapat_undangan_dibatalkan': return renderRapatDibatalkan(item);
        case 'disetujui': return renderKunjunganDisetujui(item);
        case 'ditolak': return renderKunjunganDitolak(item);
        case 'menunggu': return renderKunjunganMenunggu(item);
        default: return '';
      }
    }).join('');
  }

  // === Role Selector ===
  if (roles.includes('admin')) {
    renderHandler = renderAdmin;
  } else if (roles.includes('frontliner')) {
    renderHandler = renderFrontliner; // share event admin
  } else if (roles.includes('pegawai')) {
    renderHandler = renderPegawai;
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

    // Fallback: kalau URL kosong/undefined (misal rapat dibatalkan), arahkan ke daftar rapat
    if (!url || url === 'undefined') {
        if (roles.includes('tamu')) {
        url = '/tamu/rapat/saya';
        } else if (roles.includes('admin')) {
        url = '/admin/users';
        } else if (roles.includes('pegawai')) {
        url = '/pegawai/rapat/saya';
        } else if (roles.includes('frontliner')) {
        url = '/frontliner/kunjungan';
        }
    }

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

})();
</script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>


  {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
