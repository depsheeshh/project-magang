@php
    // Cek halaman auth
    $isAuthPage = Request::is('login') ||
                  Request::is('register') ||
                  Request::is('password/*');
@endphp

<div>
  <nav class="navbar navbar-expand-lg bg-navy text-uppercase fixed-top" id="mainNav">
    <div class="container">

      {{-- Brand dengan logo + teks --}}
      <a class="navbar-brand d-flex align-items-center text-white" href="/">
        <img src="{{ asset('img/logo.png') }}"
             alt="Logo Perusahaan"
             class="me-2"
             style="height: 32px; width: auto;" />
        <span class="fw-bold">Buku Tamu Digital</span>
      </a>

      <button class="navbar-toggler text-uppercase font-weight-bold bg-secondary text-white rounded"
              type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive"
              aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        Menu <i class="fas fa-bars"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ms-auto">

          {{-- Jika halaman saat ini adalah root/home --}}
          @if(request()->is('/'))
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-3 rounded text-white" href="#fitur">Fitur</a>
            </li>
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-3 rounded text-white" href="#alur">Alur Penggunaan</a>
            </li>
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-3 rounded text-white" href="#tentang">Tentang</a>
            </li>
          @endif

          {{-- Jika user sudah login --}}
          @if(Auth::check())
            <li class="nav-item dropdown mx-0 mx-lg-1">
              <a class="nav-link dropdown-toggle py-3 px-3 rounded text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user"></i> {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                  @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('frontliner') || Auth::user()->hasRole('pegawai') || Auth::user()->hasRole('tamu'))
                    <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                      <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                  @elseif(Auth::user()->hasRole('user'))
                    <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                      <i class="fas fa-tachometer-alt"></i> Dashboard User
                    </a>
                  @else
                    <a class="dropdown-item" href="{{ url('/') }}">
                      <i class="fas fa-home"></i> Home
                    </a>
                  @endif
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </li>
              </ul>
            </li>
          @else
            {{-- Menu login --}}
            <li class="nav-item mx-0 mx-lg-1">
              <a class="nav-link py-3 px-3 rounded text-white {{ $isAuthPage ? 'active' : '' }}"
                 href="{{ route('login') }}" title="Login">
                <i class="fas fa-sign-in-alt"></i>
              </a>
            </li>
          @endif

        </ul>
      </div>
    </div>
  </nav>
</div>
