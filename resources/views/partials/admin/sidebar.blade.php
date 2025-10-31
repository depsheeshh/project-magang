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
            <a class="nav-link" href="{{ route('admin.users.index') }}">Daftar User</a>
            </li>
            <li class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.roles.index') }}">Daftar Role</a>
            </li>
            <li class="{{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.permissions.index') }}">Daftar Permission</a>
            </li>
        </ul>
        </li>
        @endcanany

        {{-- Master Data --}}
        @canany(['pegawai.view','bidang.view','jabatan.view','reports.view'])
            <li class="dropdown {{ request()->routeIs('admin.pegawai.*') || request()->routeIs('admin.bidang.*') || request()->routeIs('admin.jabatan.*') || request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
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
                @can('reports.view')
                <li class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.laporan.index') }}">
                    <i class="fas fa-file-alt"></i> Laporan
                </a>
                </li>
                @endcan
            </ul>
            </li>
        @endcanany


        <li class="menu-header">Fitur</li>

        {{-- Menu Survey --}}
        @can('surveys.view')
            <li class="dropdown {{ request()->routeIs('admin.surveys.*') || request()->routeIs('admin.survey_links.*') ? 'active' : '' }}">
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
            </ul>
            </li>
        @endcan


        {{-- Menu Rapat --}}
        @can('rapat.view')
            <li class="dropdown {{ request()->routeIs('admin.rapat.*') || request()->routeIs('admin.instansi.*') ? 'active' : '' }}">
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
            <span>Rapat Hari Ini</span>
        </a>
        </li>
      @endrole

      {{-- Menu khusus Pegawai --}}
        @role('pegawai')
        <li class="menu-header">Pegawai</li>

        @can('pegawai.visits.view')
            <li class="{{ request()->is('pegawai/kunjungan/notifikasi*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pegawai.kunjungan.notifikasi') }}">
                <i class="fas fa-bell"></i> <span>Notifikasi Tamu</span>
            </a>
            </li>
        @endcan

        @can('pegawai.visits.details')
            <li class="{{ request()->is('pegawai/kunjungan/riwayat*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pegawai.kunjungan.riwayat') }}">
                <i class="fas fa-history"></i> <span>Riwayat Kunjungan</span>
            </a>
            </li>
        @endcan

        {{-- ✅ Agenda Rapat Saya --}}
        @can('pegawai.rapat.view')
            <li class="{{ request()->is('pegawai/rapat-saya*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pegawai.rapat.index') }}">
                <i class="fas fa-calendar-alt"></i> <span>Agenda Rapat Saya</span>
            </a>
            </li>
        @endcan
        @endrole


      {{-- Menu khusus Tamu --}}
      @role('tamu')
        <li class="menu-header">Tamu</li>
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
        <li class="{{ request()->is('tamu/rapat-saya') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tamu.rapat.saya') }}">
                <i class="fas fa-handshake"></i> <span>Agenda Rapat Saya</span>
            </a>
        </li>
      @endrole
    </ul>
  </aside>
</div>
