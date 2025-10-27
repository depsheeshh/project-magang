@extends('layouts.admin')

@section('title','Detail & Check-in Rapat')
@section('page-title','Detail & Check-in Rapat')

@section('content')
<div class="container mt-4">

  {{-- Detail rapat --}}
  <div class="card shadow-sm mb-3">
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

      {{-- Status undangan --}}
      <p>
        <strong>Peserta:</strong> {{ $undangan->user->name }} <br>
        <strong>Instansi:</strong> {{ $undangan->user->instansi->nama_instansi ?? '-' }} <br>
        <strong>Status Kehadiran:</strong>
        @if($undangan->status_kehadiran === 'pending')
            <span class="badge badge-warning">Belum Check-in</span>
        @elseif($undangan->status_kehadiran === 'hadir')
            <span class="badge badge-success">Sudah Check-in</span>
            <small class="text-muted d-block">
            {{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i') : '' }}
            </small>
        @else
            <span class="badge badge-danger">Tidak Hadir</span>
        @endif
        </p>

    </div>
  </div>

  {{-- Tombol check-in hanya muncul kalau status masih pending --}}
  @if($undangan->status_kehadiran === 'pending')
    <form id="checkinForm" action="{{ route('tamu.rapat.checkin', $rapat->id) }}" method="POST">
      @csrf
      <input type="hidden" name="latitude">
      <input type="hidden" name="longitude">

      <button type="button" class="btn btn-success" onclick="doCheckin()">
        <i class="fas fa-sign-in-alt"></i> Lakukan Check-in
      </button>
    </form>
  @endif

  <a href="{{ route('tamu.rapat.saya') }}" class="btn btn-secondary mt-2">
    <i class="fas fa-arrow-left"></i> Kembali ke Rapat Saya
  </a>
</div>
@endsection

@push('scripts')
<script>
function doCheckin() {
  if (!navigator.geolocation) {
    alert('Perangkat tidak mendukung geolokasi.');
    return;
  }
  navigator.geolocation.getCurrentPosition(function(pos){
    const form = document.getElementById('checkinForm');
    form.latitude.value = pos.coords.latitude.toFixed(6);
    form.longitude.value = pos.coords.longitude.toFixed(6);
    form.submit();
  }, function(err){
    alert('Gagal mengambil lokasi: ' + err.message);
  }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 });
}
</script>
@endpush
