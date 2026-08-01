<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard.index') }}">Buku Tamu Digital</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ route('dashboard.index') }}">BTD</a>
    </div>

    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
        <li class="{{ request()->is('dashboard') || request()->is('tamu/dashboard') ? 'active' : '' }}">
        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('frontliner') || Auth::user()->hasRole('pegawai') || Auth::user()->hasRole('tamu'))
            <a class="nav-link" href="{{ route('dashboard.index') }}">
              <i class="fas fa-fire"></i> <span>Dashboard</span>
            </a>
        @endif
        </li>

      {{-- Menu khusus Admin --}}
        @role('admin')
        <li class="menu-header">Manajemen Data</li>

        {{-- Data User --}}
        @canany(['users.view','roles.view','permissions.view'])
        <li class="dropdown {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown">
            <i class="fas fa-users"></i> <span>Data User</span>
        </a>
        <ul class="dropdown-menu">
            <li class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.users.index') }}"><i class="fa-solid fa-user-group"></i> Daftar User</a>
            </li>
            <li class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.roles.index') }}"><i class="fa-solid fa-user-shield"></i> Daftar Role</a>
            </li>
            <li class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.permissions.index') }}"><i class="fa-solid fa-key"></i> Daftar Permission</a>
            </li>
        </ul>
        </li>
        @endcanany

        {{-- Master Data --}}
        @canany(['pegawai.view','bidang.view','jabatan.view'])
            <li class="dropdown {{ request()->routeIs('admin.pegawai.*') || request()->routeIs('admin.bidang.*') || request()->routeIs('admin.jabatan.*') ? 'active' : ''}}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-database"></i> <span>Master Data</span>
            </a>
            <ul class="dropdown-menu">
                @can('pegawai.view')
                <li class="{{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.pegawai.index') }}">
                    <i class="fas fa-id-card"></i> Data Pegawai
                </a>
                </li>
                @endcan
                @can('bidang.view')
                <li class="{{ request()->routeIs('admin.bidang.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.bidang.index') }}">
                    <i class="fas fa-building"></i> Data Bidang
                </a>
                </li>
                @endcan
                @can('jabatan.view')
                <li class="{{ request()->routeIs('admin.jabatan.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.jabatan.index') }}">
                    <i class="fas fa-id-badge"></i> Data Jabatan
                </a>
                </li>
                @endcan
            </ul>
            </li>
        @endcanany

        {{-- Menu Tamu --}}
        @canany(['tamu.view','kunjungan.view','reports.view'])
        <li class="menu-header">Tamu</li>
            <li class="dropdown {{ request()->routeIs('admin.tamu.*') || request()->routeIs('admin.kunjungan.*')|| request()->routeIs('admin.laporan.*') ? 'active' : ''}}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-user-check"></i> <span>Kelola Tamu</span>
            </a>
            <ul class="dropdown-menu">
                @can('tamu.view')
                <li class="{{ request()->routeIs('admin.tamu.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.tamu.index') }}">
                    <i class="fas fa-user-friends"></i> Data Tamu
                </a>
                </li>
                @endcan
                @can('reports.view')
                <li class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.laporan.index') }}">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
                </li>
                @endcan
                {{-- @can('kunjungan.view')
                <li class="{{ request()->routeIs('admin.kunjungan.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.kunjungan.index') }}">
                    <i class="fas fa-calendar-check"></i> Kunjungan Tamu
                </a>
                </li>
                @endcan --}}
            </ul>
            </li>
            @endcanany


        <li class="menu-header">Fitur</li>

        {{-- Menu Apel Pagi --}}
        <li class="{{ request()->routeIs('admin.apelpagi.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.apelpagi.index') }}">
            <i class="fas fa-sun"></i> <span>Apel Pagi</span>
        </a>
        </li>

        {{-- Menu Survey --}}
        @can('surveys.view')
            <li class="dropdown {{ request()->routeIs('admin.surveys.*') || request()->routeIs('admin.survey_links.*') || request()->routeIs('admin.survey-rapat.*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-comment-dots"></i> <span>Survey</span>
            </a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('admin.surveys.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.surveys.index') }}">
                    <i class="fas fa-list"></i> Daftar Survey
                </a>
                </li>
                <li class="{{ request()->routeIs('admin.survey_links.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.survey_links.index') }}">
                    <i class="fas fa-link"></i> Daftar Link SKM
                </a>
                </li>
                <li class="{{ request()->routeIs('admin.surveys.rekap') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.surveys.rekap') }}">
                    <i class="fas fa-chart-bar"></i> Rekap Survey
                </a>
                </li>
                {{-- 🔥 Tambahan Survey Rapat --}}
                <li class="{{ request()->routeIs('admin.survey-rapat.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.survey-rapat.index') }}">
                    <i class="fas fa-poll"></i> Survey Rapat
                </a>
                </li>
            </ul>
            </li>
            @endcan



        {{-- Menu Rapat --}}
        @can('rapat.view')
            <li class="dropdown {{ request()->routeIs('admin.rapat.*') || request()->routeIs('admin.instansi.*') || request()->routeIs('admin.kantor.*') || request()->routeIs('admin.ruangan.*')? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
                <i class="fas fa-handshake"></i> <span>Rapat</span>
            </a>
            <ul class="dropdown-menu">
                <li class="{{ request()->routeIs('admin.rapat.index') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.rapat.index') }}">
                        <i class="fas fa-list"></i> Manajemen Rapat
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.instansi.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.instansi.index') }}">
                        <i class="fas fa-building"></i> Data Instansi
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.kantor.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.kantor.index') }}">
                        <i class="fas fa-city"></i> Data Kantor
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.ruangan.index') }}">
                        <i class="fas fa-door-open"></i> Data Ruangan
                    </a>
                </li>
                <li class="{{ request()->routeIs('admin.rapat.rekap') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('admin.rapat.rekap') }}">
                        <i class="fas fa-chart-bar"></i> Rekap Rapat
                    </a>
                </li>
            </ul>
            </li>
        @endcan


        @can('logs.view')
            <li class="menu-header">History Logs</li>
            {{-- History Logs --}}
             <li class="{{ request()->routeIs('admin.history_logs.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.history_logs.index') }}">
                    <i class="fas fa-book"></i> <span class="menu-text">Logs</span>
                </a>
            </li>
        @endcan
        @endrole


      {{-- Menu khusus Frontliner --}}
      @role('frontliner')
        <li class="menu-header">Frontliner</li>

        <li class="{{ request()->is('frontliner/kunjungan') && !request()->has('status') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('frontliner.kunjungan.index') }}">
            <i class="fas fa-list"></i>
            <span>Daftar Semua Kunjungan</span>
        </a>
        </li>

        <li class="{{ request()->is('frontliner/rapat') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('frontliner.rapat.index') }}">
            <i class="fas fa-calendar-check"></i>
            <span>Daftar Rapat</span>
        </a>
        </li>
        <li class="{{ request()->is('frontliner/rapat/hari-ini') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('frontliner.rapat.today') }}">
            <i class="fas fa-calendar-day"></i> <span>Rapat Hari Ini</span>
        </a>
        </li>
         <li class="{{ request()->is('frontliner/apelpagi') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('frontliner.apelpagi.index') }}">
            <i class="fas fa-sun"></i>
            <span>Apel Pagi</span>
            </a>
        </li>
      @endrole

      {{-- Menu khusus Pegawai --}}
        @role('pegawai')
        <li class="menu-header">Pegawai</li>

        {{-- Notifikasi Tamu --}}
        @if(Auth::user()->pegawai)
            <li class="@activeIfRoute('pegawai.kunjungan.notifikasi')">
            <a class="nav-link" href="{{ route('pegawai.kunjungan.notifikasi') }}">
                <i class="fas fa-bell"></i> <span>Notifikasi Tamu</span>
            </a>
            </li>
        @else
            <li>
            <a class="nav-link disabled" href="#" data-toggle="tooltip" title="Menu terkunci, hubungi admin DKIS">
                <i class="fas fa-lock text-muted"></i> <span class="text-muted">Notifikasi Tamu</span>
            </a>
            </li>
        @endif

        {{-- Riwayat Kunjungan --}}
        @if(Auth::user()->pegawai)
            <li class="@activeIfRoute('pegawai.kunjungan.riwayat')">
            <a class="nav-link" href="{{ route('pegawai.kunjungan.riwayat') }}">
                <i class="fas fa-history"></i> <span>Riwayat Kunjungan</span>
            </a>
            </li>
        @else
            <li>
            <a class="nav-link disabled" href="#" data-toggle="tooltip" title="Menu terkunci, hubungi admin DKIS">
                <i class="fas fa-lock text-muted"></i> <span class="text-muted">Riwayat Kunjungan</span>
            </a>
            </li>
        @endif

        <li class="@activeIfRoute(
            'pegawai.agenda.rapat',
            'pegawai.rapat.scan',
            'pegawai.rapat.detail',
            'pegawai.rapat.checkin.token',
            'pegawai.rapat.checkout'
        )">
            <a class="nav-link" href="{{ route('pegawai.agenda.rapat') }}">
            <i class="fas fa-calendar-alt"></i> <span>Agenda Rapat Saya</span>
            </a>
        </li>

        @endrole


      {{-- Menu khusus Tamu --}}
      @role('tamu')
        <li class="menu-header">Tamu</li>

        @if(Auth::user()->tamu)
            <li class="{{ request()->is('tamu/kunjungan/create') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tamu.kunjungan.create') }}">
                <i class="fas fa-plus"></i> <span>Tambah Kunjungan</span>
            </a>
            </li>
            <li class="{{ request()->is('tamu/kunjungan/status*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tamu.kunjungan.status') }}">
                <i class="fas fa-clipboard-list"></i> <span>Status Kunjungan</span>
            </a>
            </li>
        @else
            <li class="nav-item">
            <div class="nav-link disabled d-flex align-items-center" style="opacity: 0.7;" data-toggle="tooltip" title="Isi form tamu terlebih dahulu untuk mengakses menu kunjungan.">
                <i class="fas fa-lock text-warning mr-2"></i>
                <div>
                <strong class="d-block">Menu Kunjungan Terkunci</strong>
                <small class="text-muted">Isi form tamu terlebih dahulu</small>
                </div>
            </div>
            </li>
        @endif

        <li class="{{ request()->is('tamu/rapat-saya') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tamu.rapat.saya') }}">
            <i class="fas fa-handshake"></i> <span>Agenda Rapat Saya</span>
            </a>
        </li>
        @endrole

    </ul>
  </aside>
</div>

