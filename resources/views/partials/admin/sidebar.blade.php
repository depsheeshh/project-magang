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
          <li class="dropdown
              {{ request()->is('admin/users*') || request()->is('admin/roles*') || request()->is('admin/permissions*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
              <i class="fas fa-users"></i> <span>Data User</span>
            </a>
            <ul class="dropdown-menu">
              @can('users.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"
                     href="{{ route('admin.users.index') }}">Daftar User</a>
                </li>
              @endcan
              @can('roles.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}"
                     href="{{ route('admin.roles.index') }}">Daftar Role</a>
                </li>
              @endcan
              @can('permissions.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}"
                     href="{{ route('admin.permissions.index') }}">Daftar Permission</a>
                </li>
              @endcan
            </ul>
          </li>
        @endcanany

        {{-- Master Data --}}
        @canany(['pegawai.view','bidang.view','jabatan.view','reports.view','surveys.view'])
          <li class="dropdown
              {{ request()->is('admin/pegawai*') || request()->is('admin/bidang*') || request()->is('admin/jabatan*') || request()->is('admin/laporan*') || request()->is('admin/surveys*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown">
              <i class="fas fa-database"></i> <span>Master Data</span>
            </a>
            <ul class="dropdown-menu">
              @can('pegawai.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/pegawai*') ? 'active' : '' }}"
                     href="{{ route('admin.pegawai.index') }}">
                    <i class="fas fa-id-card"></i> Data Pegawai
                  </a>
                </li>
              @endcan
              @can('bidang.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/bidang*') ? 'active' : '' }}"
                     href="{{ route('admin.bidang.index') }}">
                    <i class="fas fa-building"></i> Data Bidang
                  </a>
                </li>
              @endcan
              @can('jabatan.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/jabatan*') ? 'active' : '' }}"
                     href="{{ route('admin.jabatan.index') }}">
                    <i class="fas fa-id-badge"></i> Data Jabatan
                  </a>
                </li>
              @endcan
              @can('reports.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}"
                     href="{{ route('admin.laporan.index') }}">
                    <i class="fas fa-file-alt"></i> Laporan
                  </a>
                </li>
              @endcan
              @can('surveys.view')
                <li>
                  <a class="nav-link {{ request()->is('admin/surveys*') ? 'active' : '' }}"
                     href="{{ route('admin.surveys.index') }}">
                    <i class="fas fa-comment-dots"></i> Survey Tamu
                  </a>
                </li>
              @endcan
            </ul>
          </li>
        @endcanany

        {{-- History Logs --}}
        @can('logs.view')
          <li>
            <a class="nav-link {{ request()->routeIs('admin.history_logs.*') ? 'active' : '' }}"
               href="{{ route('admin.history_logs.index') }}">
              <i class="fas fa-book"></i> Logs
            </a>
          </li>
        @endcan
      @endrole

      {{-- Menu khusus Frontliner --}}
      @role('frontliner')
        <li class="menu-header">Frontliner</li>
        <li class="{{ request()->is('frontliner/kunjungan') && !request()->has('status') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('frontliner.kunjungan.index') }}">
              <i class="fas fa-list"></i> <span>Daftar Semua Kunjungan</span>
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
      @endrole
    </ul>
  </aside>
</div>
