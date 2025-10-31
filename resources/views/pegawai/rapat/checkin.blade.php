@extends('layouts.admin')

@section('title','Detail Rapat')
@section('page-title','Detail Rapat')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>{{ $rapat->judul }}</h4>
  </div>
  <div class="card-body">
    <p>
      <strong>Waktu:</strong>
      {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
      s/d
      {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
    </p>
    <p><strong>Lokasi:</strong> {{ $rapat->lokasi ?? '-' }}</p>
    <p><strong>Status Kehadiran:</strong>
      @if($undangan->status_kehadiran === null || $undangan->status_kehadiran === 'pending')
        <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Belum Check-in</span>
      @elseif($undangan->status_kehadiran === 'hadir')
        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Sudah Check-in</span>
        <small class="text-muted d-block">
          {{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i') : '' }}
        </small>
      @elseif($undangan->status_kehadiran === 'selesai')
        <span class="badge bg-secondary"><i class="fas fa-flag-checkered"></i> Selesai</span>
      @else
        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tidak Hadir</span>
      @endif
    </p>

    {{-- Tombol aksi --}}
    @if($undangan->status_kehadiran === null || $undangan->status_kehadiran === 'pending')
      <form action="{{ route('pegawai.rapat.checkin',$rapat->id) }}" method="POST">
        @csrf
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <button type="submit" class="btn btn-success">
          <i class="fas fa-sign-in-alt"></i> Check-in Sekarang
        </button>
      </form>
    @elseif($undangan->status_kehadiran === 'hadir')
        <form action="{{ route('pegawai.rapat.checkout',$rapat->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Check-out
            </button>
        </form>
    @endif


    <a href="{{ route('pegawai.rapat.index') }}" class="btn btn-secondary mt-3">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>
</div>

{{-- Script ambil lokasi --}}
<script>
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(pos) {
    document.getElementById('latitude').value = pos.coords.latitude;
    document.getElementById('longitude').value = pos.coords.longitude;
  });
}
</script>
@endsection
