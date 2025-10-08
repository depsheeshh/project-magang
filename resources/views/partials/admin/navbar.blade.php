<nav class="navbar navbar-expand-lg main-navbar">
  <!-- Left Section -->
  <ul class="navbar-nav mr-auto">
    <li class="nav-item">
      <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- Right Section -->
  <ul class="navbar-nav navbar-right d-flex align-items-center">
    <!-- Dark/Light Mode Toggle -->
    <li class="nav-item d-flex align-items-center">
      <a href="#" id="toggle-darkmode" class="nav-link nav-link-lg d-flex align-items-center">
        <i id="darkmode-icon" class="fas fa-moon fa-lg"></i>
      </a>
    </li>

    <!-- User Dropdown -->
    <li class="nav-item dropdown">
      <a href="#" data-toggle="dropdown"
         class="nav-link dropdown-toggle nav-link-lg nav-link-user d-flex align-items-center">
        <img alt="image"
             src="{{ Auth::user()->avatar_url ?? asset('admin/assets/img/avatar/avatar-1.png') }}"
             class="rounded-circle mr-2" width="35" height="35">
        <span class="d-none d-lg-inline font-weight-bold">
          {{ Auth::user()->name }}
          <small class="text-muted">({{ Auth::user()->roles->pluck('name')->implode(', ') }})</small>
        </span>
      </a>

      <div class="dropdown-menu dropdown-menu-right">
        <a href="{{ route('profile') }}" class="dropdown-item has-icon">
          <i class="fas fa-user"></i> Profil Saya
        </a>
        <a href="{{ route('password.change') }}" class="dropdown-item has-icon">
          <i class="fas fa-key"></i> Ubah Password
        </a>
        <a href="{{ url('/') }}" class="dropdown-item has-icon">
          <i class="fas fa-home"></i> Landing Page
        </a>

        <div class="dropdown-divider"></div>
        <form action="{{ route('logout') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="dropdown-item text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </div>
    </li>
  </ul>
</nav>
