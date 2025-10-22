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
      <h2 class="fw-bold text-light mb-1" style="letter-spacing: 1px;">SELAMAT DATANG</h2>
      <p class="text-light mb-4 fs-5">
        Transformasi digital untuk sistem buku tamu Anda. Cepat, aman, dan mudah digunakan.
        Kelola kunjungan dengan lebih efisien dan profesional.
      </p>

      <!-- Subheading -->
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

      <!-- Tombol CTA -->
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-4" data-aos="fade-up" data-aos-delay="400">
        @auth
            @php $roles = Auth::user()->roles->pluck('name')->toArray(); @endphp

            @if(empty($roles))
              <a href="{{ route('tamu.scan') }}" class="btn-cta btn-cta-animated-luxury">
                <i class="fas fa-pen me-2"></i> Isi Buku Tamu Pertama
              </a>
            @elseif(in_array('tamu', $roles))
              <a href="{{ route('dashboard.index') }}" class="btn-cta btn-cta-green">
                <i class="fas fa-home me-2"></i> Ke Dashboard
              </a>
              <a href="{{ route('tamu.scan') }}" class="btn-cta btn-cta-primary">
                <i class="fas fa-pen me-2"></i> Isi Buku Tamu Lagi
              </a>
              <a href="{{ route('tamu.rapat.saya') }}" class="btn-cta btn-cta-animated-techy">
                <i class="fas fa-handshake me-2"></i> Agenda Rapat Saya
              </a>
            @elseif(in_array('pegawai', $roles))
              <a href="{{ route('dashboard.index') }}" class="btn-cta btn-cta-green">
                <i class="fas fa-home me-2"></i> Ke Dashboard
              </a>
            @elseif(in_array('admin', $roles) || in_array('frontliner', $roles))
              <a href="{{ route('dashboard.index') }}" class="btn-cta btn-cta-green">
                <i class="fas fa-home me-2"></i> Ke Dashboard
              </a>
              <a href="{{ route('qrcode.tamu') }}" class="btn-cta btn-cta-animated-energetic">
                <i class="fas fa-qrcode me-2"></i> Lihat QR Code Buku Tamu
              </a>
            @endif
        @else
            <a href="{{ route('tamu.scan') }}" class="btn-cta btn-cta-primary">
              <i class="fas fa-book me-2"></i> Isi Buku Tamu
              <i class="fas fa-arrow-right ms-1"></i>
            </a>
            <a href="{{ route('login') }}" class="btn-cta btn-cta-outline">
              <i class="fas fa-sign-in-alt me-2"></i> Login
            </a>
        @endauth
      </div>

      <!-- Statistik -->
      {{-- <div class="d-flex flex-wrap justify-content-center gap-4 mt-5">
        <div class="stat-box">
          <i class="fas fa-users fa-lg text-info mb-2"></i>
          <h5 class="mb-0 fw-bold text-light">5000+</h5>
          <small class="text-secondary">Pengunjung</small>
        </div>
      </div> --}}
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

    /* === CTA BUTTON FINAL === */
    .btn-cta {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-weight: 700;
      font-size: 1.1rem;
      border-radius: 14px;
      padding: 16px 38px;
      letter-spacing: 0.4px;
      border: none;
      text-decoration: none !important;
      position: relative;
      overflow: hidden;
      transition: all 0.35s ease;
      box-shadow: 0 6px 18px rgba(0,0,0,0.35);
      isolation: isolate;
    }

    /* Border glow animasi */
    .btn-cta::before {
      content: "";
      position: absolute;
      inset: -2px;
      border-radius: inherit;
      padding: 2px;
      background: linear-gradient(120deg, #60a5fa, #34d399, #fbbf24, #f472b6);
      background-size: 300% 300%;
      animation: borderGlow 6s linear infinite;
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
              mask-composite: exclude;
      z-index: -1;
    }

    .btn-cta:hover {
      transform: translateY(-4px) scale(1.03);
      box-shadow: 0 10px 28px rgba(0,0,0,0.45);
    }

    /* Ripple effect */
    .btn-cta .ripple {
      position: absolute;
      border-radius: 50%;
      transform: scale(0);
      animation: rippleAnim 0.6s linear;
      background-color: rgba(255, 255, 255, 0.4);
      pointer-events: none;
    }


    /* Ikon animasi */
    .btn-cta i {
      transition: transform 0.35s ease;
    }
    .btn-cta:hover i {
      transform: translateX(6px) rotate(5deg);
    }

    /* Variasi warna */
    .btn-cta-green {
    background: linear-gradient(135deg, #03471c, #056528, #046059);
    color: #fff;
    }
    .btn-cta-blue {
    background: linear-gradient(135deg, #3b82f6, #2563eb, #7c3aed);
    color: #fff;
    }
    .btn-cta-yellow {
    background: linear-gradient(135deg, #facc15, #f97316, #ec4899);
    color: #111827;
    }
    .btn-cta-primary {
    background: linear-gradient(135deg, #2563eb, #3b82f6, #06b6d4);
    color: #fff;
    }
    .btn-cta-glass {
      background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
      color: #e2e8f0;
      border: 1px solid rgba(255,255,255,0.2);
      backdrop-filter: blur(14px);
    }
    .btn-cta-glass:hover {
      background: linear-gradient(135deg, rgba(255,255,255,0.15), rgba(255,255,255,0.05));
      transform: translateY(-2px);
    }

    .btn-cta-outline {
      background: transparent;
      color: #fff;
      border: 2px solid rgba(255,255,255,0.35);
    }
    .btn-cta-outline:hover {
      background: rgba(255,255,255,0.12);
      transform: translateY(-2px);
    }

    .btn-cta-animated-techy {
    background: linear-gradient(270deg, #3b82f6, #06b6d4, #7c3aed);
    background-size: 400% 400%;
    animation: gradientFlow 8s ease infinite;
    color: #fff;
    }
    .btn-cta-animated-energetic {
    background: linear-gradient(270deg, #ef4444, #f97316, #facc15);
    background-size: 400% 400%;
    animation: gradientFlow 8s ease infinite;
    color: #fff;
    }
    .btn-cta-animated-luxury {
    background: linear-gradient(270deg, #fbbf24, #a855f7, #1f2937);
    background-size: 400% 400%;
    animation: gradientFlow 10s ease infinite;
    color: #fff;
    }

    /* Animasi gradien bergerak */
    @keyframes gradientFlow {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
    }

    /* Animasi border glow */
    @keyframes borderGlow {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }


    @keyframes rippleAnim {
      to {
        transform: scale(4);
        opacity: 0;
      }
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <script>
    // Ripple effect JS
    document.querySelectorAll('.btn-cta').forEach(btn => {
      btn.addEventListener('click', function(e) {
        const circle = document.createElement('span');
        circle.classList.add('ripple');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        circle.style.width = circle.style.height = size + 'px';
        circle.style.left = (e.clientX - rect.left - size / 2) + 'px';
        circle.style.top = (e.clientY - rect.top - size / 2) + 'px';

        this.appendChild(circle);
        setTimeout(() => circle.remove(), 600);
      });
    });
  </script>
</header>
