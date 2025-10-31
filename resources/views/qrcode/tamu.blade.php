@extends('layouts.app')

@section('title', 'QR Code Buku Tamu')

@section('content')
<style>
body {
  background: linear-gradient(135deg, #0d1b2a, #000814 60%, #001d3d);
  color: #e0e6f1;
  font-family: 'Poppins', sans-serif;
}

.card {
  border: none;
  border-radius: 18px;
  background: rgba(15, 25, 48, 0.9);
  backdrop-filter: blur(12px);
  box-shadow: 0 10px 30px rgba(0, 145, 255, 0.25);
  overflow: hidden;
  transition: all 0.3s ease;
}
.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(0, 180, 255, 0.35);
}

.card-header {
  background: linear-gradient(90deg, #0077ff, #00b4ff);
  color: #fff;
  text-align: center;
  font-weight: 600;
  padding: 1.2rem 0;
  box-shadow: 0 4px 12px rgba(0, 132, 255, 0.3);
}

.qr-box {
  display: inline-block;
  padding: 18px;
  border-radius: 16px;
  background: radial-gradient(circle at top, #001233, #000814);
  box-shadow: 0 0 25px rgba(0, 180, 255, 0.3);
  transition: all 0.3s ease;
}
.qr-box:hover {
  transform: scale(1.03);
  box-shadow: 0 0 40px rgba(0, 220, 255, 0.4);
}

.steps h6 {
  color: #00b4ff;
  font-weight: 600;
  margin-bottom: 0.75rem;
}
.steps ol {
  color: #a7b8d8;
  font-size: 14px;
  line-height: 1.7;
  padding-left: 1.2rem;

  /* 🌟 Multi kolom di layar besar */
  column-count: 2;
  column-gap: 2rem;
}
.steps li {
  margin-bottom: 0.6rem;
  break-inside: avoid; /* biar 1 item tidak terpotong antar kolom */
}
.steps li::marker {
  color: #00bfff;
  font-weight: bold;
}

@media (max-width: 768px) {
  .steps ol {
    column-count: 1; /* di HP balik ke 1 kolom */
  }
  .card-body { padding: 2rem 1.5rem; }
}

.btn {
  border-radius: 10px;
  font-weight: 600;
  padding: 10px 22px;
  transition: all 0.3s ease;
}
.btn-success {
  background: linear-gradient(135deg, #2dd36f, #1ea85b);
  border: none;
  color: #fff;
  box-shadow: 0 0 15px rgba(0, 255, 130, 0.25);
}
.btn-success:hover {
  background: linear-gradient(135deg, #36e07a, #1bc965);
  box-shadow: 0 0 25px rgba(0, 255, 130, 0.5);
  transform: translateY(-2px);
}
.btn-outline-primary {
  color: #00bfff;
  border: 1px solid #00bfff;
}
.btn-outline-primary:hover {
  background: linear-gradient(135deg, #00bfff, #0077ff);
  color: #fff;
  box-shadow: 0 0 25px rgba(0, 157, 255, 0.4);
}
</style>

<div class="container d-flex justify-content-center align-items-center mt-5 py-5" style="min-height: 90vh;">
  <div class="card shadow-lg border-0 w-100 mt-5" style="max-width: 620px;">
    <div class="card-header">
      <h4 class="mb-0"><i class="bi bi-qr-code me-2"></i> Scan QR Code Buku Tamu</h4>
    </div>

    <div class="card-body text-center py-5">

      {{-- Kotak QR Code --}}
      <div class="qr-box mb-4">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->margin(1)->generate(route('tamu.form')) !!}
      </div>

      {{-- Langkah-langkah --}}
      <div class="steps text-start px-3">
        <h6><i class="bi bi-list-check me-1"></i> Panduan Penggunaan:</h6>
        <ol>
            <li data-aos="fade-up"><i class="bi bi-phone"></i> Buka aplikasi web <b>Buku Tamu Digital</b>.</li>
            <li data-aos="fade-up" data-aos-delay="100"><i class="bi bi-qr-code-scan"></i> Arahkan kamera ponsel untuk <b>scan QR Code</b>.</li>
            <li data-aos="fade-up" data-aos-delay="200"><i class="bi bi-pencil-square"></i> Isi data diri, pilih pegawai tujuan, dan tuliskan keperluan.</li>
            <li data-aos="fade-up" data-aos-delay="300"><i class="bi bi-hourglass-split"></i> Tunggu konfirmasi dari frontliner.</li>
            <li data-aos="fade-up" data-aos-delay="400"><i class="bi bi-x-circle"></i> Jika pegawai tidak tersedia → Anda mendapat pemberitahuan penolakan.</li>
            <li data-aos="fade-up" data-aos-delay="500"><i class="bi bi-check-circle"></i> Jika pegawai tersedia → Anda dipersilakan masuk dan bertemu.</li>
            <li data-aos="fade-up" data-aos-delay="600"><i class="bi bi-door-open"></i> Setelah selesai, tekan tombol <b>“Selesai”</b> di aplikasi.</li>
        </ol>
      </div>

      {{-- Tombol Aksi --}}
      <div class="mt-4">
        <a href="{{ route('qrcode.tamu.pdf') }}" class="btn btn-success me-2">
          <i class="bi bi-file-earmark-pdf"></i> Unduh QR Code (PDF)
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-primary">
          <i class="bi bi-arrow-left"></i> Kembali
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
