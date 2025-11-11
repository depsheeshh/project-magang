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

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">


  <link rel="icon" type="image/x-icon" href="{{ asset('img/logo.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('assets/favicon.ico') }}" />

    <link rel="stylesheet" href="{{ asset('css/style-dark.css') }}">

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

    list.innerHTML = items.map(item => {
      // cek kalau event instansi_baru → render khusus
      if (item.event === 'instansi_baru') {
        return `
          <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
               data-id="${item.id}" data-url="/admin/instansi">
            <div class="notif-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                 style="width:38px;height:38px;">
              <i class="fas fa-building"></i>
            </div>
            <div class="notif-content flex-fill">
              <div class="notif-title font-weight-bold">Instansi Baru Ditambahkan</div>
              <div class="notif-sub small">
                ${(item.nama_instansi || '-')} oleh ${(item.user || 'Peserta')}
              </div>
              <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu || '')}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        `;
      }

     if (item.event === 'user_baru') {
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
            </div>
        `;
        }



      if (item.event === 'survey_pelayanan') {
        return `
            <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
                data-id="${item.id}" data-url="/admin/surveys">
            <div class="notif-icon bg-purple text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                style="width:38px;height:38px;">
                <i class="fas fa-poll"></i>
            </div>
            <div class="notif-content flex-fill">
                <div class="notif-title font-weight-bold">Survey Pelayanan Baru</div>
                <div class="notif-sub small">
                ${(item.judul || 'Survey')} oleh ${(item.user || 'Peserta')}
                </div>
                <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu || '')}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
                <i class="fas fa-trash"></i>
            </button>
            </div>
        `;
        }

        if (item.event === 'rapat_undangan') {
        return `
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
        `;
        }


      // cek kalau event survey_baru → render khusus
        if (item.event === 'survey_baru') {
        return `
            <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
                data-id="${item.id}" data-url="/admin/surveys">
            <div class="notif-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                style="width:38px;height:38px;">
                <i class="fas fa-comment-dots"></i>
            </div>
            <div class="notif-content flex-fill">
                <div class="notif-title font-weight-bold">Survey Baru Ditambahkan</div>
                <div class="notif-sub small">
                ${(item.judul || 'Survey')} oleh ${(item.user || 'Admin/Peserta')}
                </div>
                <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu || '')}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
                <i class="fas fa-trash"></i>
            </button>
            </div>
        `;
        }

      // default render
      return `
        <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
             data-id="${item.id}" ${url ? `data-url="${url}"` : ''}>
          <div class="notif-icon ${color} text-white rounded-circle d-flex align-items-center justify-content-center me-3"
               style="width:38px;height:38px;">
            <i class="fas ${icon}"></i>
          </div>
          <div class="notif-content flex-fill">
            <div class="notif-title font-weight-bold">${item.nama || 'Notifikasi'}</div>
            <div class="notif-sub small">
              ${(item.instansi || '')} ${(item.keperluan ? ' • ' + item.keperluan : '')}
            </div>
            <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu || '')}</div>
          </div>
          <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      `;
    }).join('');
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
      // 🔔 Undangan rapat baru
      if (item.event === 'rapat_undangan') {
        return `
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
        `;
      }

      // 🔔 Undangan rapat dibatalkan
      if (item.event === 'rapat_undangan_dibatalkan') {
        return `
          <div class="notif-item d-flex align-items-start border-bottom py-2 px-2"
               data-id="${item.id}">
            <div class="notif-icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                 style="width:38px;height:38px;">
              <i class="fas fa-ban"></i>
            </div>
            <div class="notif-content flex-fill">
              <div class="notif-title font-weight-bold">Undangan Rapat Dibatalkan</div>
              <div class="notif-sub small">${item.judul || 'Rapat'} • ${(item.waktu || '')}</div>
              <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu_notif || '')}</div>
            </div>
            <button class="btn btn-sm btn-link text-danger delete-notif" data-id="${item.id}">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        `;
      }

      // default kunjungan
      let icon = 'fa-clock',
          color = 'bg-warning',
          label = 'Menunggu',
          labelClass = 'status-menunggu';

      if (item.event === 'disetujui') {
        icon = 'fa-check-circle'; color = 'bg-success'; label = 'Disetujui'; labelClass = 'status-disetujui';
      }
      if (item.event === 'ditolak') {
        icon = 'fa-times-circle'; color = 'bg-danger'; label = 'Ditolak'; labelClass = 'status-ditolak';
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
            <div class="notif-time small"><i class="fas fa-clock"></i> ${(item.waktu || '')}</div>
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

    // Fallback: kalau URL kosong/undefined (misal rapat dibatalkan), arahkan ke daftar rapat
    if (!url) {
        url = '/tamu/rapat/saya';
    } else if (!url || url === 'undefined') {
            url = '/admin/users';
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


  {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</body>
</html>
