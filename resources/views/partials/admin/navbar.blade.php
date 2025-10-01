<nav class="navbar navbar-expand-lg main-navbar">
  <ul class="navbar-nav mr-auto">
    <li>
      <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <ul class="navbar-nav navbar-right">
    {{-- Tombol toggle dark/light mode --}}
    <li class="nav-item">
      <a href="#" id="toggle-darkmode" class="nav-link nav-link-lg">
        <i id="darkmode-icon" class="fas fa-moon"></i>
      </a>
    </li>

    {{-- Dropdown user --}}
    <li class="dropdown">
      <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img alt="image" src="{{ asset('admin/assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
        <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->name }}</div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('password.change') }}" class="dropdown-item has-icon">
          <i class="fas fa-key"></i> Ubah Password
        </a>

        {{-- Tambahan tombol balik ke landing page --}}
        <a href="{{ url('/') }}" class="dropdown-item has-icon">
          <i class="fas fa-home"></i> Landing Page
        </a>

        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="POST" class="dropdown-item">
          @csrf
          <button type="submit" class="btn btn-link text-danger p-0 m-0">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </div>
    </li>
  </ul>
</nav>
