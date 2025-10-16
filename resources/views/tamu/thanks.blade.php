@extends('layouts.app')

@section('title','Terima Kasih')

@section('content')
<style>
.page-section {
  padding-top: 100px;
  padding-bottom: 100px;
  background: radial-gradient(circle at top, #0d1117, #121826);
  min-height: 100vh;
  color: #fff;
}

.card {
  border-radius: 20px;
  background: #1a1f2e;
  box-shadow: 0 0 25px rgba(100, 120, 255, 0.3), inset 0 0 20px rgba(120, 140, 255, 0.1);
  border: 1px solid rgba(130, 150, 255, 0.2);
  transition: all 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0 40px rgba(120, 140, 255, 0.5);
}

.btn-primary {
  background: linear-gradient(135deg, #5c6cff, #9b8cff);
  border: none;
  padding: 12px 28px;
  border-radius: 50px;
  font-weight: 600;
  color: #fff;
  box-shadow: 0 0 15px rgba(120, 140, 255, 0.4);
  transition: 0.3s ease;
}
.btn-primary:hover {
  background: linear-gradient(135deg, #6c7aff, #b1a6ff);
  box-shadow: 0 0 25px rgba(120, 140, 255, 0.6);
}
</style>

<section class="page-section d-flex align-items-center justify-content-center text-center">
  <div class="container">
    <div class="card border-0 mx-auto" style="max-width: 600px;" data-aos="zoom-in">
      <div class="card-body py-5">
        <div class="mb-4" data-aos="fade-down">
          <i class="fas fa-check-circle fa-5x text-success"></i>
        </div>
        <h2 class="text-light text-uppercase mb-3" data-aos="fade-up">Terima Kasih!</h2>
        <p class="lead text-light mb-4" data-aos="fade-up" data-aos-delay="100">
          Data kunjungan Anda telah berhasil disimpan.<br>
          Silakan menunggu konfirmasi dari frontliner kami.
        </p>

        <a href="{{ url('/') }}" class="btn btn-primary btn-lg" data-aos="zoom-in" data-aos-delay="200">
          <i class="fas fa-home"></i> Kembali ke Halaman Utama
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
