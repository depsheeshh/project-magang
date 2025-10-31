<footer class="footer text-center py-5 position-relative"
        style="
          background: linear-gradient(180deg, #020510 0%, #00186c 100%);
          backdrop-filter: blur(12px);
          border-top: 1px solid rgba(255,255,255,0.08);
          box-shadow: 0 -4px 30px rgba(0,0,0,0.3);
        ">
  <div class="container text-light-50">
    <div class="row justify-content-between align-items-start mb-5">

      <!-- Lokasi -->
      <div class="col-md-6 mb-4 mb-md-0 text-start">
        <h6 class="fw-semibold mb-3 text-uppercase text-gradient">
          <i class="fas fa-map-marker-alt me-2"></i> Lokasi
        </h6>
        <p class="mb-0 small">
          Jl. DR. Sudarsono No.40, Kesambi<br>
          Kec. Kesambi, Kota Cirebon<br>
          Jawa Barat 45134
        </p>
      </div>

      <!-- Sosial Media -->
      <div class="col-md-6 text-md-end text-start">
        <h6 class="fw-semibold mb-3 text-uppercase text-gradient">
          <i class="fas fa-share-alt me-2"></i> Ikuti Kami
        </h6>
        <div class="d-flex justify-content-md-end justify-content-start gap-3">

          <!-- Instagram -->
          <a href="https://www.instagram.com/dkiskotacirebon/" target="_blank"
             class="social-btn instagram"><i class="fab fa-instagram"></i></a>

          <!-- Twitter -->
          <a href="https://x.com/dkiscirebonkota" target="_blank"
             class="social-btn twitter"><i class="fab fa-twitter"></i></a>

          <!-- Tombol Web Resmi -->
            <a href="https://dkis.cirebonkota.go.id/" target="_blank" class="social-btn official">
            <i class="fas fa-globe"></i>
            </a>
        </div>
      </div>
    </div>

    <hr class="border-secondary opacity-25">

    <!-- Copyright -->
    <div class="d-flex flex-column align-items-center mt-4">
      <div class="d-flex align-items-center mb-2">
        <div class="icon-glow d-flex align-items-center justify-content-center me-2">
          <i class="fas fa-book-open text-white"></i>
        </div>
        <span class="fw-semibold text-light">Buku Tamu Digital</span>
      </div>
      <p class="small text-secondary mb-0">
        © {{ date('Y') }} Buku Tamu Digital. All rights reserved.
      </p>
    </div>
  </div>

  <!-- Style -->
  <style>
    .text-gradient {
      background: linear-gradient(90deg, #38bdf8, #818cf8, #c084fc);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    .social-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 44px;
      height: 44px;
      border-radius: 50%;
      color: white;
      font-size: 18px;
      transition: all 0.3s ease;
    }

    .social-btn.instagram {
      background: linear-gradient(135deg, #f58529, #dd2a7b, #8134af);
      box-shadow: 0 0 15px rgba(221, 42, 123, 0.4);
    }

    .social-btn.twitter {
      background: linear-gradient(135deg, #1da1f2, #0e71c8);
      box-shadow: 0 0 15px rgba(29, 161, 242, 0.4);
    }

    .social-btn.official {
        background: linear-gradient(135deg, #0f172a, #1e293b); /* gradasi abu gelap elegan */
        color: #ffffff;
        border: 1px solid #334155;
        padding: 10px 16px;
        border-radius: 50%;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 0 15px rgba(15, 23, 42, 0.3);
        }

        .social-btn.official:hover {
        background: linear-gradient(135deg, #1e293b, #334155);
        transform: translateY(-2px);
        box-shadow: 0 0 20px rgba(15, 23, 42, 0.5);
        }

        .social-btn.official i {
        font-size: 16px;
        }

    .social-btn:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 25px rgba(59, 130, 246, 0.8);
    }

    .icon-glow {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      background: linear-gradient(135deg, #38bdf8, #3b82f6);
      box-shadow: 0 0 15px rgba(56, 189, 248, 0.6);
    }

    .text-light-50 {
      color: rgba(255, 255, 255, 0.75);
    }
  </style>
</footer>
