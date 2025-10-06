@extends('layouts.app')

@section('title','Terima Kasih')

@section('content')
<section class="page-section bg-light d-flex align-items-center mt-5" style="min-height: 80vh;">
  <div class="container text-center mt-5">
    <div class="card shadow-lg border-0 mx-auto" style="max-width: 600px;">
      <div class="card-body py-5">
        <div class="mb-4">
          <i class="fas fa-check-circle fa-5x text-success"></i>
        </div>
        <h2 class="text-success text-uppercase mb-3">Terima Kasih!</h2>
        <p class="lead mb-4">
          Data kunjungan Anda telah berhasil disimpan.<br>
          Silakan menunggu konfirmasi dari frontliner kami.
        </p>

        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-home"></i> Kembali ke Halaman Utama
        </a>
      </div>
    </div>
  </div>
</section>
@endsection
