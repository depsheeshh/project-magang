<header class="text-center text-white position-relative overflow-hidden"
    style="background: linear-gradient(180deg, #0f172a 0%, #081a2e 100%);
           padding: 130px 0 100px;">
  <div class="container position-relative">
    <div class="glass-card p-5 mx-auto" style="max-width: 720px;">
      <!-- Badge -->
      <span class="badge rounded-pill bg-primary-subtle text-light fw-semibold mb-3 px-3 py-2"
            style="background: rgba(37, 99, 235, 0.2); border: 1px solid rgba(37,99,235,0.3);">
        <i class="fas fa-sparkles me-1 text-info"></i> Sistem Tamu Modern & Efisien
      </span>

      <!-- Heading -->
      <h2 class="fw-bold text-primary mb-1" style="letter-spacing: 1px;">SELAMAT DATANG</h2>
      <p class="text-light mb-4 fs-5">
        Transformasi digital untuk sistem buku tamu Anda. Cepat, aman, dan mudah digunakan.
        Kelola kunjungan dengan lebih efisien dan profesional.
      </p>

      <!-- Subheading (dengan kondisi login) -->
      @auth
        <p class="masthead-subheading font-weight-light mb-4 fs-5" data-aos="fade-up" data-aos-delay="250">
          Halo <strong>{{ Auth::user()->name }}</strong>, Anda login sebagai
          <strong>{{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'User Baru' }}</strong>.
        </p>
      @else
        <p class="masthead-subheading font-weight-light mb-4 fs-5" data-aos="fade-up" data-aos-delay="250">
          Catat kunjungan Anda dengan cepat, aman, dan efisien.
        </p>
      @endauth

      <!-- Tombol aksi (dinamis berdasarkan role) -->
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-3" data-aos="fade-up" data-aos-delay="400">
        @auth
          @php
            $roles = Auth::user()->roles->pluck('name')->toArray();
          @endphp

          {{-- User baru --}}
          @if(empty($roles))
            <a href="{{ route('tamu.scan') }}" class="btn btn-secondary btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
              <i class="fas fa-pen"></i> Isi Buku Tamu Pertama
            </a>

          {{-- Role: tamu --}}
          @elseif(in_array('tamu', $roles))
            <a href="{{ route('dashboard.index') }}" class="btn btn-success btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
              <i class="fas fa-home"></i> Ke Dashboard
            </a>
            <a href="{{ route('tamu.scan') }}" class="btn btn-secondary btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
              <i class="fas fa-pen"></i> Isi Buku Tamu Lagi
            </a>

          {{-- Role: pegawai --}}
          @elseif(in_array('pegawai', $roles))
            <a href="{{ route('dashboard.index') }}" class="btn btn-success btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
              <i class="fas fa-home"></i> Ke Dashboard
            </a>

          {{-- Role: admin atau frontliner --}}
          @elseif(in_array('admin', $roles) || in_array('frontliner', $roles))
            <a href="{{ route('dashboard.index') }}" class="btn btn-success btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
              <i class="fas fa-home"></i> Ke Dashboard
            </a>
            <a href="{{ route('qrcode.tamu') }}" class="btn btn-warning btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
              <i class="fas fa-qrcode"></i> Lihat QR Code Buku Tamu
            </a>
          @endif
        @else
          {{-- Belum login --}}
          <a href="{{ route('tamu.scan') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
            <i class="fas fa-book"></i> Isi Buku Tamu
            <i class="fas fa-arrow-right ms-1"></i>
          </a>
          <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
            <i class="fas fa-sign-in-alt"></i> Login
          </a>
        @endauth
      </div>

      <!-- Statistik ringkas -->
      <div class="d-flex flex-wrap justify-content-center gap-4 mt-5">
        <div class="stat-box">
          <i class="fas fa-users fa-lg text-info mb-2"></i>
          <h5 class="mb-0 fw-bold text-light">5000+</h5>
          <small class="text-secondary">Pengunjung</small>
        </div>
        {{-- <div class="stat-box">
          <i class="fas fa-bolt fa-lg text-info mb-2"></i>
          <h5 class="mb-0 fw-bold text-light">99.9%</h5>
          <small class="text-secondary">Uptime</small>
        </div>
        <div class="stat-box">
          <i class="fas fa-star fa-lg text-info mb-2"></i>
          <h5 class="mb-0 fw-bold text-light">4.9</h5>
          <small class="text-secondary">Rating</small>
        </div> --}}
      </div>
    </div>
  </div>

  <style>
    .glass-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(18px);
      border-radius: 24px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      animation: fadeUp 0.8s ease-out;
    }

    .stat-box {
      width: 180px;
      padding: 18px 10px;
      background: rgba(15, 23, 42, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.05);
      border-radius: 16px;
      backdrop-filter: blur(12px);
      transition: all 0.3s ease;
    }

    .stat-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</header>
