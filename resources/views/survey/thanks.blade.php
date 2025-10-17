@extends('layouts.guest')

@section('title','Terima Kasih')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm text-center">
      <div class="card-body py-5">
        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
        <h3 class="mb-3">Terima Kasih!</h3>
        <p class="lead">Survey kepuasan Anda sudah kami terima.</p>
        <p class="text-muted">Masukan Anda sangat berarti untuk meningkatkan pelayanan kami.</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">
          <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
