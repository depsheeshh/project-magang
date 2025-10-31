{{-- 🌌 Alur Tamu --}}
<section id="alur" class="py-5 position-relative overflow-hidden"
  style="background: linear-gradient(180deg, #040b18 0%, #020511 100%);
         color: #e2e8f0;">

  <!-- Background Partikel Neon -->
  <canvas id="alurStarsCanvas"></canvas>

  <div class="container text-center position-relative" style="z-index: 2;">
    <button class="btn btn-outline-light mb-4 px-4 py-2 rounded-pill"
      style="backdrop-filter: blur(8px);
             background: rgba(255,255,255,0.08);
             border-color: rgba(255,255,255,0.2);">
      🚀 Alur Penggunaan Tamu
    </button>

    <h3 class="fw-bold mb-5 text-gradient">Cara Kerja Sistem Tamu</h3>

    <div class="row justify-content-center g-4">
      @php
        $stepsTamu = [
          ['icon'=>'fa-qrcode','title'=>'Scan QR','desc'=>'Scan QR Code di lokasi untuk memulai','color'=>'#38bdf8'],
          ['icon'=>'fa-id-card','title'=>'Isi Data Tamu','desc'=>'Lengkapi data kunjungan dengan mudah','color'=>'#06b6d4'],
          ['icon'=>'fa-user-check','title'=>'Verifikasi','desc'=>'Sistem memverifikasi data Anda','color'=>'#22c55e'],
          ['icon'=>'fa-user-plus','title'=>'Buat Tamu','desc'=>'Data tamu berhasil tercatat','color'=>'#fbbf24'],
          ['icon'=>'fa-door-open','title'=>'Check Out','desc'=>'Check out saat selesai berkunjung','color'=>'#ef4444']
        ];
      @endphp

      @foreach($stepsTamu as $index => $step)
        <div class="col-6 col-md-4 col-lg-2 position-relative" data-aos="fade-up" data-aos-delay="{{ $index * 120 }}">
          <div class="alur-card h-100 p-4 rounded-4">
            <div class="alur-icon mb-3" style="color: {{ $step['color'] }}">
              <i class="fas {{ $step['icon'] }} fa-2x"></i>
            </div>
            <h6 class="fw-bold text-uppercase">{{ $step['title'] }}</h6>
            <p class="small text-light opacity-75 mb-0">{{ $step['desc'] }}</p>
          </div>

          @if($index < count($stepsTamu) - 1)
            <span class="alur-line"></span>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- 🌠 Alur Rapat --}}
<section id="alur-rapat" class="py-5 position-relative overflow-hidden"
  style="background: linear-gradient(180deg, #020511 0%, #030814 100%);
         color: #e2e8f0;">

  <canvas id="rapatStarsCanvas"></canvas>

  <div class="container text-center position-relative" style="z-index: 2;">
    <button class="btn btn-outline-light mb-4 px-4 py-2 rounded-pill"
      style="backdrop-filter: blur(8px);
             background: rgba(255,255,255,0.08);
             border-color: rgba(255,255,255,0.2);">
      💼 Alur Penggunaan Rapat
    </button>

    <h3 class="fw-bold mb-5 text-gradient">Cara Kerja Sistem Rapat</h3>

    <div class="row justify-content-center g-4">
      @php
        $stepsRapat = [
          ['icon'=>'fa-calendar-plus','title'=>'Buat Undangan','desc'=>'Admin membuat undangan rapat dengan detail lengkap','color'=>'#60a5fa'],
          ['icon'=>'fa-users','title'=>'Undang Peserta','desc'=>'Peserta rapat diundang melalui sistem','color'=>'#34d399'],
          ['icon'=>'fa-bell','title'=>'Notifikasi','desc'=>'Peserta menerima notifikasi undangan rapat','color'=>'#facc15'],
          ['icon'=>'fa-handshake','title'=>'Pelaksanaan','desc'=>'Rapat dilaksanakan sesuai jadwal','color'=>'#06b6d4'],
          ['icon'=>'fa-file-alt','title'=>'Notulen','desc'=>'Sistem mencatat notulen dan hasil rapat','color'=>'#f472b6'],
          ['icon'=>'fa-archive','title'=>'Arsip','desc'=>'Data rapat tersimpan rapi dalam arsip sistem','color'=>'#ef4444']
        ];
      @endphp

      @foreach($stepsRapat as $index => $step)
        <div class="col-6 col-md-4 col-lg-2 position-relative" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
          <div class="alur-card h-100 p-4 rounded-4">
            <div class="alur-icon mb-3" style="color: {{ $step['color'] }}">
              <i class="fas {{ $step['icon'] }} fa-2x"></i>
            </div>
            <h6 class="fw-bold text-uppercase">{{ $step['title'] }}</h6>
            <p class="small text-light opacity-75 mb-0">{{ $step['desc'] }}</p>
          </div>

          @if($index < count($stepsRapat) - 1)
            <span class="alur-line"></span>
          @endif
        </div>
      @endforeach
    </div>
  </div>

  <style>
    /* === Style umum === */
    .text-gradient {
      background: linear-gradient(90deg, #60a5fa, #a855f7, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .alur-card {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.08);
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }
    .alur-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 20px rgba(59,130,246,0.35);
    }

    .alur-icon i {
      text-shadow: 0 0 10px rgba(255,255,255,0.3);
    }

    /* === Garis penghubung antar step === */
    .alur-line {
      position: absolute;
      top: 50%;
      right: -20px;
      width: 40px;
      height: 2px;
      background: linear-gradient(90deg, rgba(59,130,246,0.3), rgba(255,255,255,0.1));
      transform: translateY(-50%);
    }

    @media (max-width: 768px) {
      .alur-line { display: none; }
    }

    /* === Canvas partikel bintang === */
    #alurStarsCanvas, #rapatStarsCanvas {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      z-index: 0;
      pointer-events: none;
    }
  </style>

  <script>
  // === Script bintang neon reuse (sama seperti hero & fitur) ===
  function initStars(canvasId, total = 80) {
    const canvas = document.getElementById(canvasId);
    const ctx = canvas.getContext("2d");
    let stars = [];
    let w, h;

    function resize() {
      w = canvas.width = window.innerWidth;
      h = canvas.height = canvas.parentElement.offsetHeight + 150;
      stars = [];
      for (let i = 0; i < total; i++) {
        stars.push({
          x: Math.random() * w,
          y: Math.random() * h,
          r: Math.random() * 2,
          color: `hsl(${Math.random() * 360}, 70%, 70%)`,
          alpha: Math.random(),
          speed: 0.1 + Math.random() * 0.2
        });
      }
    }

    function animate() {
      ctx.clearRect(0, 0, w, h);
      stars.forEach(s => {
        ctx.beginPath();
        ctx.arc(s.x, s.y, s.r, 0, 2 * Math.PI);
        ctx.fillStyle = `rgba(${hexToRgb(s.color)}, ${s.alpha})`;
        ctx.shadowBlur = 12;
        ctx.shadowColor = s.color;
        ctx.fill();
        s.y -= s.speed;
        if (s.y < -5) s.y = h + 5;
      });
      requestAnimationFrame(animate);
    }

    function hexToRgb(hsl) {
      const temp = document.createElement("div");
      temp.style.color = hsl;
      document.body.appendChild(temp);
      const rgb = getComputedStyle(temp).color;
      document.body.removeChild(temp);
      return rgb.match(/\d+/g).slice(0,3).join(",");
    }

    resize();
    animate();
    window.addEventListener("resize", resize);
  }

  initStars("alurStarsCanvas");
  initStars("rapatStarsCanvas");
  </script>
</section>
