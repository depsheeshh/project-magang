@extends('layouts.admin')

@section('title','Hasil Check-in Rapat')
@section('page-title','Hasil Check-in Rapat')

@section('content')
<div class="container mt-4">

  {{-- Ringkasan status kehadiran --}}
  <div class="card shadow-sm mb-4 text-center">
    <div class="card-body">
      @if($status === 'success')
        <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
        <h5 class="mb-1 text-success">Status Kehadiran: Hadir</h5>
        <p class="text-muted mb-0">{{ $message }}</p>
      @elseif($status === 'error')
        <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
        <h5 class="mb-1 text-danger">Status Kehadiran: Tidak Hadir</h5>
        <p class="text-muted mb-0">{{ $message }}</p>
      @else
        <i class="fas fa-info-circle fa-3x text-secondary mb-2"></i>
        <h5 class="mb-1 text-secondary">Status Kehadiran: Tidak Diketahui</h5>
        <p class="text-muted mb-0">{{ $message ?? 'Status check-in tidak diketahui.' }}</p>
      @endif
    </div>
  </div>

  {{-- Detail rapat --}}
  <div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center bg-light">
      <h5 class="mb-0"><i class="fas fa-handshake"></i> Detail Rapat</h5>
      @if($status === 'success')
        <span class="badge badge-success">Hadir</span>
      @elseif($status === 'error')
        <span class="badge badge-danger">Tidak Hadir</span>
      @else
        <span class="badge badge-secondary">Tidak Diketahui</span>
      @endif
    </div>
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">Judul</dt>
        <dd class="col-sm-9">{{ $rapat->judul }}</dd>

        <dt class="col-sm-3">Waktu</dt>
        <dd class="col-sm-9">
          {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
          s/d
          {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
        </dd>

        <dt class="col-sm-3">Lokasi</dt>
        <dd class="col-sm-9">
          {{ $rapat->lokasi ?? '-' }} <br>
          <small class="text-muted">
            Lat: {{ $rapat->latitude ?? '-' }},
            Lon: {{ $rapat->longitude ?? '-' }},
            Radius: {{ $rapat->radius ?? '-' }} m
          </small>
        </dd>
      </dl>
    </div>
  </div>

  {{-- Tombol navigasi --}}
  <div class="d-flex justify-content-between">
    <a href="{{ route('tamu.rapat.saya') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali ke Rapat Saya
    </a>
    <a href="{{ route('tamu.rapat.show', $rapat->id) }}" class="btn btn-info">
      <i class="fas fa-info-circle"></i> Lihat Detail Rapat
    </a>
  </div>
</div>
@endsection
