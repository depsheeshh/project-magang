<div>
  <header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">
      <!-- Logo / Avatar -->
      <img class="masthead-avatar mb-5"
           src="{{ asset('assets/img/portfolio/4300_7_03.png') }}"
           alt="Buku Tamu" style="width: 180px;" />

      <!-- Judul -->
      <h1 class="masthead-heading text-uppercase mb-0">Selamat Datang</h1>

      <!-- Divider -->
      <div class="divider-custom divider-light" data-aos="zoom-in" data-aos-delay="200">
        <div class="divider-custom-line"></div>
        <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
        <div class="divider-custom-line"></div>
      </div>

      <!-- Subheading -->
      @auth
        <p class="masthead-subheading font-weight-light mb-4">
          Halo <strong>{{ Auth::user()->name }}</strong>,
          Anda login sebagai <strong>{{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'User Baru' }}</strong>.
        </p>
      @else
        <p class="masthead-subheading font-weight-light mb-4">
          Mudah, cepat, dan efisien untuk mencatat kunjungan Anda.
        </p>
      @endauth

      <!-- Tombol Aksi -->
      <div data-aos="fade-up" data-aos-delay="400">
        @auth
          @php
            $roles = Auth::user()->roles->pluck('name')->toArray();
          @endphp

          {{-- User baru (belum punya role apapun) --}}
          @if(empty($roles))
            <a href="{{ route('tamu.scan') }}" class="btn btn-secondary btn-lg me-2">
              Isi Buku Tamu Pertama
            </a>

          {{-- Role tamu --}}
          @elseif(in_array('tamu', $roles))
            <a href="{{ route('dashboard.index') }}" class="btn btn-success btn-lg me-2">
              Ke Dashboard
            </a>
            <a href="{{ route('tamu.scan') }}" class="btn btn-secondary btn-lg me-2">
              Isi Buku Tamu Lagi
            </a>

          {{-- Role pegawai/frontliner/admin --}}
          @else
            <a href="{{ route('dashboard.index') }}" class="btn btn-success btn-lg me-2">
              Ke Dashboard
            </a>
          @endif

        @else
          {{-- Guest (belum login) --}}
          <a href="{{ route('tamu.scan') }}" class="btn btn-secondary btn-lg me-2">
            Isi Buku Tamu
          </a>
          <a href="{{ route('login') }}" class="btn btn-light btn-lg">
            Login
          </a>
        @endauth
      </div>
    </div>
  </header>
</div>
