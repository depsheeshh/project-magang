<section id="alur" class="py-5" style="background: linear-gradient(180deg, #05101f 0%, #030814 100%);">
  <div class="container text-center text-light">
    <button class="btn btn-outline-light mb-4 px-4 py-2 rounded-pill" style="backdrop-filter: blur(8px); background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2);">
      Alur Penggunaan
    </button>

    <h3 class="fw-bold mb-5">Cara Kerja Sistem</h3>

    <div class="row justify-content-center g-4">
      @php
        $steps = [
          ['icon'=>'fa-qrcode','title'=>'Scan QR','desc'=>'Scan QR Code di lokasi untuk memulai'],
          ['icon'=>'fa-id-card','title'=>'Isi Data Tamu','desc'=>'Lengkapi data kunjungan dengan mudah'],
          ['icon'=>'fa-user-check','title'=>'Verifikasi','desc'=>'Sistem memverifikasi data Anda'],
          ['icon'=>'fa-user-plus','title'=>'Buat Tamu','desc'=>'Data tamu berhasil tercatat'],
          ['icon'=>'fa-door-open','title'=>'Check Out','desc'=>'Check out saat selesai berkunjung']
        ];
      @endphp

      @foreach($steps as $index => $step)
        <div class="col-6 col-md-4 col-lg-2" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
          <div class="p-4 rounded-4 shadow-sm h-100" style="backdrop-filter: blur(10px); background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); transition: all .3s;">
            <i class="fas {{ $step['icon'] }} fa-2x mb-3 text-info"></i>
            <h6 class="text-uppercase fw-bold">{{ $step['title'] }}</h6>
            <p class="small text-light opacity-75">{{ $step['desc'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
