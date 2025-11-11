<header class="hero-section text-center text-white position-relative overflow-hidden mt-3">
  <!-- Canvas bintang -->
  <canvas id="heroStarsCanvas"></canvas>

  <div class="container position-relative z-2">
    <div class="glass-card p-5 mx-auto" style="max-width: 720px;">
      <!-- Badge -->
      <span class="badge badge-glow mb-3">
        <i class="fas fa-bolt me-1 text-warning"></i> Sistem Tamu Modern & Efisien
      </span>

      <!-- Heading Animasi -->
      <h1 class="hero-title mb-3">
        <span class="glow-text">SELAMAT DATANG</span>
      </h1>
      <p class="lead mb-4 text-light">
        Transformasi digital untuk sistem buku tamu Anda.<br>
        Cepat, aman, dan mudah digunakan.
      </p>

      @auth
        <p class="text-info mb-4 fw-semibold">
          Halo <strong>{{ Auth::user()->name }}</strong>, Anda login sebagai
          <strong>{{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'User Baru' }}</strong>.
        </p>
      @else
        <p class="text-muted mb-4 fs-5">
          Catat kunjungan Anda dengan cepat, aman, dan efisien.
        </p>
      @endauth

      <!-- CTA Buttons -->
      <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
        @auth
          @php $roles = Auth::user()->roles->pluck('name')->toArray(); @endphp
          @if(empty($roles))
            <a href="{{ route('tamu.scan') }}" class="btn-cta btn-cta-luxury">
              <i class="fas fa-pen me-2"></i> Isi Buku Tamu Pertama
            </a>
            <a href="{{ route('tamu.rapat.scan') }}" class="btn-cta btn-cta-energetic">
                <i class="fas fa-qrcode me-2"></i> Check-in Rapat Eksternal
            </a>
          @elseif(in_array('tamu', $roles))
            <a href="{{ route('dashboard.index') }}" class="btn-cta btn-cta-green">
              <i class="fas fa-home me-2"></i> Ke Dashboard
            </a>
            <a href="{{ route('tamu.scan') }}" class="btn-cta btn-cta-primary">
              <i class="fas fa-book me-2"></i> Isi Buku Tamu Lagi
            </a>
            <a href="{{ route('tamu.rapat.saya') }}" class="btn-cta btn-cta-animated-luxury">
              <i class="fas fa-handshake me-2"></i> Agenda Rapat Saya
            </a>
            <a href="{{ route('tamu.rapat.scan') }}" class="btn-cta btn-cta-energetic">
                <i class="fas fa-qrcode me-2"></i> Check-in Rapat Eksternal
            </a>
          @elseif(in_array('pegawai', $roles))
            <a href="{{ route('dashboard.index') }}" class="btn-cta btn-cta-green">
              <i class="fas fa-home me-2"></i> Ke Dashboard
            </a>
          @elseif(in_array('admin', $roles) || in_array('frontliner', $roles))
            <a href="{{ route('dashboard.index') }}" class="btn-cta btn-cta-green">
              <i class="fas fa-home me-2"></i> Ke Dashboard
            </a>
            <a href="{{ route('qrcode.tamu') }}" class="btn-cta btn-cta-energetic">
              <i class="fas fa-qrcode me-2"></i> Lihat QR Code Buku Tamu
            </a>
          @endif
        @else
          <a href="{{ route('tamu.scan') }}" class="btn-cta btn-cta-primary">
            <i class="fas fa-book me-2"></i> Isi Buku Tamu
          </a>
          <a href="{{ route('tamu.rapat.scan') }}" class="btn-cta btn-cta-energetic">
            <i class="fas fa-qrcode me-2"></i> Check-in Rapat Eksternal
        </a>
          <a href="{{ route('login') }}" class="btn-cta btn-cta-outline">
            <i class="fas fa-sign-in-alt me-2"></i> Login
          </a>
        @endauth
      </div>
    </div>
  </div>

  <style>
    /* === Layout & Background === */
    .hero-section {
      position: relative;
      background: linear-gradient(180deg, #043ca4, #0d2456 85%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    #heroStarsCanvas {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      z-index: 1;
      pointer-events: none;
    }

    .z-2 { z-index: 2; }

    /* === Glass Card === */
    .glass-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(16px);
      border-radius: 24px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      animation: fadeUp 1s ease-out;
    }

    /* === Badge === */
    .badge-glow {
      background: rgba(56, 189, 248, 0.2);
      border: 1px solid rgba(56, 189, 248, 0.3);
      border-radius: 12px;
      padding: 8px 16px;
      font-weight: 600;
      color: #93c5fd;
      letter-spacing: 0.5px;
      box-shadow: 0 0 20px rgba(56, 189, 248, 0.3);
      animation: pulseBadge 3s ease-in-out infinite;
    }

    @keyframes pulseBadge {
      0%, 100% { box-shadow: 0 0 15px rgba(56, 189, 248, 0.3); }
      50% { box-shadow: 0 0 35px rgba(56, 189, 248, 0.7); }
    }

    /* === Title === */
    .hero-title {
      font-size: 3.2rem;
      font-weight: 800;
      letter-spacing: 2px;
    }

    .glow-text {
      background: linear-gradient(90deg, #3b82f6, #06b6d4, #f59e0b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 0 35px rgba(59, 130, 246, 0.6);
      animation: gradientMove 6s infinite alternate ease-in-out;
    }

    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      100% { background-position: 100% 50%; }
    }

    /* === Buttons (sama seperti versi sebelumnya) === */
    .btn-cta {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 14px 34px;
      border-radius: 14px;
      font-weight: 600;
      text-decoration: none;
      transition: 0.3s ease;
    }
    .btn-cta:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.35); }

    .btn-cta-primary { background: linear-gradient(135deg, #2563eb, #06b6d4); color: #fff; }
    .btn-cta-green { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .btn-cta-energetic { background: linear-gradient(135deg, #f97316, #facc15); color: #fff; }
    .btn-cta-outline { border: 2px solid rgba(255,255,255,0.3); color: #fff; }

    .btn-cta-luxury, .btn-cta-animated-luxury {
      background: linear-gradient(270deg, #fbbf24, #a855f7, #1f2937);
      background-size: 400% 400%;
      animation: gradientFlow 10s ease infinite;
      color: #fff;
    }

    @keyframes gradientFlow {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <!-- JS Bintang Neon -->
  <script>
    function initStars(canvasId, total = 120) {
      const canvas = document.getElementById(canvasId);
      const ctx = canvas.getContext("2d");
      let stars = [];
      let w, h;

      function resize() {
        w = canvas.width = window.innerWidth;
        h = canvas.height = window.innerHeight;
        stars = [];
        for (let i = 0; i < total; i++) {
          stars.push({
            x: Math.random() * w,
            y: Math.random() * h,
            r: Math.random() * 2,
            color: `hsl(${Math.random() * 360}, 70%, 70%)`,
            alpha: Math.random(),
            speed: 0.05 + Math.random() * 0.25
          });
        }
      }

      function animate() {
        ctx.clearRect(0, 0, w, h);
        stars.forEach(s => {
          ctx.beginPath();
          ctx.arc(s.x, s.y, s.r, 0, 2 * Math.PI);
          ctx.fillStyle = s.color;
          ctx.globalAlpha = s.alpha;
          ctx.shadowBlur = 12;
          ctx.shadowColor = s.color;
          ctx.fill();
          s.y -= s.speed;
          if (s.y < -5) s.y = h + 5;
        });
        requestAnimationFrame(animate);
      }

      resize();
      animate();
      window.addEventListener("resize", resize);
    }

    initStars("heroStarsCanvas");
  </script>
</header>
