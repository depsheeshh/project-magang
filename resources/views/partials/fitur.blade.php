<section id="fitur" class="py-5 position-relative overflow-hidden"
  style="background: linear-gradient(180deg, #081021 0%, #040b18 100%);
         color: #e2e8f0;">

  <!-- 🔮 Background partikel neon seperti hero -->
  <canvas id="starsCanvas"></canvas>

  <div class="container text-center position-relative" style="z-index: 2;">
    <!-- Badge -->
    <button class="btn btn-outline-light mb-4 px-4 py-2 rounded-pill"
      style="backdrop-filter: blur(8px);
             background: rgba(255,255,255,0.08);
             border-color: rgba(255,255,255,0.2);
             letter-spacing: 0.5px;">
      🌟 Fitur Utama
    </button>

    <!-- Title -->
    <h3 class="fw-bold mb-5 text-gradient">Mengapa Memilih Buku Tamu Digital?</h3>

    <div class="row justify-content-center g-4">
      @php
        $fitur = [
          ['icon'=>'fa-qrcode','title'=>'Check-in Cepat','desc'=>'Tamu cukup scan QR Code atau isi form digital, tanpa antre panjang.','color'=>'#38bdf8'],
          ['icon'=>'fa-shield-alt','title'=>'Data Aman & Terenkripsi','desc'=>'Seluruh data kunjungan tersimpan aman dan hanya bisa diakses oleh admin berwenang.','color'=>'#22c55e'],
          ['icon'=>'fa-bell','title'=>'Notifikasi & Rapat','desc'=>'Terintegrasi dengan undangan rapat, notifikasi status, dan check-in khusus rapat.','color'=>'#facc15'],
          ['icon'=>'fa-chart-line','title'=>'Rekap & Laporan','desc'=>'Pantau statistik kunjungan, ekspor laporan, dan rekap survey secara real-time.','color'=>'#60a5fa'],
          ['icon'=>'fa-users-cog','title'=>'Multi-Role','desc'=>'Mendukung peran admin, frontliner, pegawai, dan tamu dengan akses sesuai kebutuhan.','color'=>'#ef4444'],
          ['icon'=>'fa-mobile-alt','title'=>'Responsif & Modern','desc'=>'Desain elegan, dark mode friendly, dan nyaman di semua perangkat.','color'=>'#06b6d4']
        ];
      @endphp

      @foreach($fitur as $index => $f)
      <div class="col-md-4" data-aos="zoom-in" data-aos-delay="{{ $index * 120 }}">
        <div class="p-4 rounded-4 shadow-sm h-100 feature-card"
          style="backdrop-filter: blur(12px);
                 background: rgba(255,255,255,0.05);
                 border: 1px solid rgba(255,255,255,0.1);
                 transition: all .3s;">
          <i class="fas {{ $f['icon'] }} fa-3x mb-3" style="color: {{ $f['color'] }}"></i>
          <h5 class="fw-bold mb-2">{{ $f['title'] }}</h5>
          <p class="opacity-75">{{ $f['desc'] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <style>
    /* ✨ Text Gradient Sama dengan Hero */
    .text-gradient {
      background: linear-gradient(90deg, #60a5fa, #a855f7, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    /* 💫 Hover Card Glow */
    .feature-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 0 25px rgba(0,150,255,0.3);
    }

    /* 🌠 Canvas Layer */
    #starsCanvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      pointer-events: none;
    }
  </style>

  <script>
  // === Script partikel bintang neon sama seperti di hero ===
  const canvas = document.getElementById("starsCanvas");
  const ctx = canvas.getContext("2d");
  let stars = [];
  let w, h;

  function resize() {
    w = canvas.width = window.innerWidth;
    h = canvas.height = canvas.parentElement.offsetHeight + 200;
    stars = [];
    for (let i = 0; i < 100; i++) {
      stars.push({
        x: Math.random() * w,
        y: Math.random() * h,
        radius: Math.random() * 2.2,
        color: `hsl(${Math.random() * 360}, 70%, 70%)`,
        alpha: Math.random(),
        speed: 0.15 + Math.random() * 0.2,
      });
    }
  }

  function animate() {
    ctx.clearRect(0, 0, w, h);
    for (let s of stars) {
      ctx.beginPath();
      ctx.arc(s.x, s.y, s.radius, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${hexToRgb(s.color)}, ${s.alpha})`;
      ctx.shadowBlur = 12;
      ctx.shadowColor = s.color;
      ctx.fill();
      s.y -= s.speed;
      if (s.y < -5) s.y = h + 5;
      s.alpha += (Math.random() - 0.5) * 0.05;
      s.alpha = Math.max(0.2, Math.min(1, s.alpha));
    }
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

  window.addEventListener("resize", resize);
  resize();
  animate();
  </script>
</section>
