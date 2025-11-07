@extends('layouts.admin')

@section('title','Rapat Saya')
@section('page-title','Rapat Saya')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4>Daftar Rapat Saya</h4>
    <a href="{{ route('pegawai.rapat.scan') }}" class="btn btn-success btn-sm">
      <i class="fas fa-qrcode"></i> Scan QR Rapat
    </a>
  </div>
  <div class="card-body">
    @forelse($rapatSaya as $rapat)
      @php $undangan = $rapat->undangan->first(); @endphp

      <div class="card mb-3 shadow-sm border">
        <div class="card-body">
          <h5 class="mb-2">{{ $rapat->judul }}</h5>
          <p class="mb-1">
            <span class="badge bg-info">
              {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
            </span>
            s/d
            <span class="badge bg-secondary">
              {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
            </span>
          </p>
          <p class="mb-2"><strong>Lokasi:</strong> {{ $rapat->lokasi ?? '-' }}</p>
          <p class="mb-2">
            <strong>Status Kehadiran:</strong>
            @if(!$undangan || $undangan->status_kehadiran === null || $undangan->status_kehadiran === 'pending')
              <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Belum Check-in</span>
            @elseif($undangan->status_kehadiran === 'hadir')
              <span class="badge bg-success"><i class="fas fa-check-circle"></i> Sudah Check-in</span>
            @elseif($undangan->status_kehadiran === 'selesai')
              <span class="badge bg-secondary"><i class="fas fa-flag-checkered"></i> Selesai</span>
            @else
              <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tidak Hadir</span>
            @endif
          </p>

          <a href="{{ route('pegawai.rapat.detail',$rapat->id) }}" class="btn btn-primary btn-sm">
            <i class="fas fa-info-circle"></i> Detail & Aksi
          </a>
        </div>
      </div>
    @empty
      <p class="text-muted">Anda belum memiliki undangan rapat.</p>
    @endforelse
  </div>
</div>
@endsection
