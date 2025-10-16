@extends('layouts.app')

@section('title', 'QR Code Buku Tamu')

@section('content')
@push('style')

@endpush
<style>
/* 🌌 Background gradient nyambung */
body {
  background: linear-gradient(135deg, #0d1b2a, #000814 60%, #001d3d);
  color: #e0e6f1;
  font-family: 'Poppins', sans-serif;
}

/* ✨ Card utama */
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

/* Header card */
.card-header {
  background: linear-gradient(90deg, #0077ff, #00b4ff);
  color: #fff;
  border: none;
  text-align: center;
  font-weight: 600;
  letter-spacing: 0.5px;
  padding: 1.2rem 0;
  box-shadow: 0 4px 12px rgba(0, 132, 255, 0.3);
}

/* QR container */
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

/* Langkah-langkah */
.steps h6 {
  color: #00b4ff;
  font-weight: 600;
  margin-bottom: 0.75rem;
}

.steps ol {
  color: #a7b8d8;
  font-size: 14px;
  line-height: 1.6;
}

.steps li::marker {
  color: #00bfff;
}

/* Tombol */
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

/* Responsive */
@media (max-width: 768px) {
  .card-body {
    padding: 2rem 1.5rem;
  }
}
</style>

<div class="container d-flex justify-content-center align-items-center mt-5 py-5" style="min-height: 90vh;">
  <div class="card shadow-lg border-0 w-100 mt-5" style="max-width: 520px;">
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
        <h6><i class="bi bi-list-check me-1"></i> Langkah-langkah:</h6>
        <ol>
          <li>Buka aplikasi web Buku Tamu Digital.</li>
          <li>Scan QR Code dengan kamera ponsel Anda.</li>
          <li>Isi data diri, pilih pegawai yang ingin ditemui, dan tuliskan keperluan.</li>
          <li>Tunggu konfirmasi dari frontliner.</li>
          <li>Jika pegawai tidak tersedia, Anda akan mendapat pemberitahuan penolakan.</li>
          <li>Jika pegawai tersedia, Anda dipersilakan masuk dan bertemu pegawai tersebut.</li>
          <li>Setelah selesai bertamu, tekan tombol <b>“Selesai”</b> di aplikasi.</li>
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
