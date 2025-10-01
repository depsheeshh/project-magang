<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <header class="masthead bg-primary text-white text-center">
      <div class="container d-flex align-items-center flex-column">
        <img class="masthead-avatar mb-5" src="{{ asset('assets/img/portfolio/4300_7_03.png') }}" alt="Buku Tamu" style="width: 180px;" />

        <h1 class="masthead-heading text-uppercase mb-0">Selamat Datang</h1>

        <div class="divider-custom divider-light">
          <div class="divider-custom-line"></div>
          <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
          <div class="divider-custom-line"></div>
        </div>

        <p class="masthead-subheading font-weight-light mb-4">
          Mudah, cepat, dan efisien untuk mencatat kunjungan Anda.
        </p>

        <div>
          {{-- Tombol Isi Buku Tamu diarahkan ke route tamu.scan --}}
          <a href="{{ route('tamu.scan') }}" class="btn btn-secondary btn-lg me-2">
            Isi Buku Tamu
          </a>

          {{-- Jika mau aktifkan login admin, tinggal buka komentar --}}
          {{-- <a href="{{ route('login') }}" class="btn btn-light btn-lg">Login Admin</a> --}}
        </div>
      </div>
    </header>
</div>
