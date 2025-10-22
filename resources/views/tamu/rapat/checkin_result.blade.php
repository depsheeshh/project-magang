@extends('layouts.admin')

@section('title','Check-in Rapat')
@section('page-title','Check-in Rapat')

@section('content')
<div class="container mt-4">

  @if($status === 'success')
    <div class="alert alert-success d-flex align-items-center">
      <i class="fas fa-check-circle fa-lg mr-2"></i>
      <div>{{ $message }}</div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="card-title mb-3">Detail Rapat</h5>
        <p><strong>Judul:</strong> {{ $rapat->judul }}</p>
        <p>
          <strong>Waktu:</strong>
          {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
          s/d
          {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
        </p>
        <p>
          <strong>Lokasi:</strong> {{ $rapat->lokasi ?? '-' }} <br>
          <small class="text-muted">
            Lat: {{ $rapat->latitude ?? '-' }},
            Lon: {{ $rapat->longitude ?? '-' }},
            Radius: {{ $rapat->radius ?? '-' }} m
          </small>
        </p>
      </div>
    </div>

  @else
    <div class="alert alert-danger d-flex align-items-center">
      <i class="fas fa-times-circle fa-lg mr-2"></i>
      <div>{{ $message }}</div>
    </div>
  @endif

  <a href="{{ route('tamu.rapat.saya') }}" class="btn btn-secondary mt-3">
    <i class="fas fa-arrow-left"></i> Kembali ke Rapat Saya
  </a>
</div>
@endsection
