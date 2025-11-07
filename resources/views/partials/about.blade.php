<section id="tentang" class="py-5 position-relative overflow-hidden"
  style="background: linear-gradient(180deg, #030814 0%, #021041 100%);
         color: #e2e8f0;">

  <!-- Canvas Bintang -->
  <canvas id="aboutStarsCanvas"></canvas>

  <div class="container text-center position-relative" data-aos="fade-up" style="z-index: 2;">
    <!-- Tombol Section -->
    <button class="btn btn-outline-light mb-4 px-4 py-2 rounded-pill"
      style="backdrop-filter: blur(8px);
             background: rgba(255,255,255,0.08);
             border-color: rgba(255,255,255,0.2);">
      🌌 Tentang Aplikasi
    </button>

    <h3 class="fw-bold mb-3 text-gradient">Buku Tamu Digital DKIS</h3>

    <div class="mx-auto p-5 rounded-4 shadow-lg about-card" style="max-width: 850px;">
      <p class="lead mb-3">
        <strong class="text-highlight">Buku Tamu Digital</strong> merupakan aplikasi resmi yang dikembangkan oleh
        <strong class="text-highlight">Dinas Komunikasi, Informatika dan Statistik (DKIS)</strong> Kota Cirebon sebagai solusi modern untuk
        pencatatan kunjungan tamu, rapat, dan survei kepuasan secara <em>paperless</em>.
      </p>

      <p class="text-light opacity-80 mb-3">
        Dengan dukungan teknologi <strong class="text-glow">QR Code</strong>, validasi lokasi berbasis radius,
        serta notifikasi real-time, aplikasi ini memastikan setiap interaksi tercatat dengan
        <span class="text-info-glow">akurat</span>, <span class="text-info-glow">aman</span>, dan
        <span class="text-info-glow">transparan</span>.
      </p>

      <p class="text-light opacity-80 mb-0">
        Aplikasi ini dirancang untuk memudahkan tamu, pegawai, dan admin dalam proses check-in,
        pengelolaan rapat, hingga pembuatan laporan resmi yang sesuai standar instansi.
        Dengan tampilan modern dan interaktif, <strong class="text-highlight">Buku Tamu Digital DKIS</strong> menjadi
        bagian dari komitmen kami menghadirkan pelayanan publik yang lebih efisien dan terpercaya.
      </p>
    </div>
  </div>

  <style>
    /* === Efek gradien dan highlight === */
    .text-gradient {
      background: linear-gradient(90deg, #38bdf8, #818cf8, #c084fc);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .text-highlight {
      color: #93c5fd;
      transition: color 0.3s;
    }
    .text-highlight:hover {
      color: #38bdf8;
    }

    .text-glow {
      color: #60a5fa;
      text-shadow: 0 0 8px rgba(96,165,250,0.7);
    }

    .text-info-glow {
      color: #38bdf8;
      text-shadow: 0 0 10px rgba(56,189,248,0.6);
      animation: glowPulse 3s infinite ease-in-out;
    }

    @keyframes glowPulse {
      0%, 100% { text-shadow: 0 0 8px rgba(56,189,248,0.4); }
      50% { text-shadow: 0 0 20px rgba(56,189,248,0.8); }
    }

    /* === Card gaya kaca modern === */
    .about-card {
      background: rgba(255,255,255,0.05);
      backdrop-filter: blur(14px);
      border: 1px solid rgba(255,255,255,0.08);
      box-shadow: 0 0 25px rgba(59,130,246,0.15),
                  inset 0 0 20px rgba(255,255,255,0.05);
      position: relative;
      overflow: hidden;
    }

    .about-card::before {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(120deg,
        rgba(59,130,246,0.15),
        rgba(147,51,234,0.1),
        rgba(6,182,212,0.1)
      );
      opacity: 0.5;
      animation: flowLight 6s linear infinite;
    }

    @keyframes flowLight {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* === Canvas bintang === */
    #aboutStarsCanvas {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      z-index: 0;
      pointer-events: none;
    }
  </style>

  <script>
  // 🌠 Animasi partikel bintang (sama seperti hero, fitur, alur)
  function initStars(canvasId, total = 90) {
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

  initStars("aboutStarsCanvas");
  </script>
</section>
