@php
$isAuthPage = Request::is('login') || Request::is('register') || Request::is('password/*');
@endphp

<nav class="navbar navbar-expand-lg fixed-top shadow-sm"
     style="background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.08);">
  <div class="container">
    {{-- Brand --}}
    <a class="navbar-brand fw-bold text-white fs-4 d-flex align-items-center" href="{{ url('/') }}">
      <img src="{{ asset('img/logo.png') }}" alt="Logo" class="me-2" style="height: 32px;">
      BukuTamu<span class="text-light">Digital</span>
    </a>

    {{-- Toggler --}}
    <button class="navbar-toggler border-0 text-white" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <i class="fas fa-bars fa-lg"></i>
    </button>

    {{-- Menu --}}
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        {{-- Menu landing page --}}
        @if(request()->is('/'))
          <li class="nav-item">
            <a class="nav-link text-white" href="#fitur">Fitur</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#alur">Alur Penggunaan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="#tentang">Tentang</a>
          </li>
        @endif

        {{-- Auth --}}
        @if(Auth::check())
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown"
               role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3">
              <li>
                @if(Auth::user()->hasAnyRole(['admin', 'frontliner', 'pegawai', 'tamu']))
                  <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                  </a>
                @else
                  <a class="dropdown-item" href="{{ url('/') }}">
                    <i class="fas fa-home me-2"></i> Home
                  </a>
                @endif
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link text-white {{ $isAuthPage ? 'active' : '' }}" href="{{ route('login') }}">
              <i class="fas fa-sign-in-alt"></i> Login
            </a>
          </li>
        @endif
      </ul>
    </div>
  </div>

  <style>
    .navbar-nav .nav-link {
      font-weight: 500;
      transition: color 0.3s ease, transform 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
      color: #38bdf8;
      transform: translateY(-2px);
    }
    .navbar-nav .nav-link.active {
      color: #60a5fa;
    }

    /* Responsive dropdown background for dark theme */
    .dropdown-menu {
      background-color: #1e293b;
    }
    .dropdown-menu .dropdown-item {
      color: #e2e8f0;
    }
    .dropdown-menu .dropdown-item:hover {
      background-color: #334155;
      color: #38bdf8;
    }

    /* Mobile menu fix */
    @media (max-width: 991px) {
      .navbar-collapse {
        background: rgba(15, 23, 42, 0.97);
        backdrop-filter: blur(12px);
        border-radius: 0 0 12px 12px;
        padding: 10px 0;
      }
      .navbar-nav .nav-link {
        padding: 10px 20px;
        text-align: center;
      }
    }
  </style>
</nav>
