@extends('layouts.admin')

@section('title','Rapat Saya')
@section('page-title','Rapat Saya')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Daftar Rapat Saya</h4>
  </div>
  <div class="card-body">
    @forelse($rapatSaya as $rapat)
      @php
        $undangan = $rapat->undangan->first();
      @endphp
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <h5 class="mb-2">{{ $rapat->judul }}</h5>
          <p class="mb-1">
            <span class="badge badge-info">
              {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
            </span>
            s/d
            <span class="badge badge-secondary">
              {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
            </span>
          </p>
          <p class="mb-3">
            Lokasi: {{ $rapat->lokasi ?? '-' }} <br>
            <small class="text-muted">
              Lat: {{ $rapat->latitude ?? '-' }},
              Lon: {{ $rapat->longitude ?? '-' }},
              Radius: {{ $rapat->radius ?? '-' }} m
            </small>
          </p>

          @if($undangan->status_kehadiran === 'pending')
            <form id="checkinForm{{ $rapat->id }}" action="{{ route('tamu.rapat.checkin', $rapat->id) }}" method="POST">
              @csrf
              <input type="hidden" name="latitude">
              <input type="hidden" name="longitude">
              <button type="button" class="btn btn-success btn-sm" onclick="doCheckin{{ $rapat->id }}()">
                <i class="fas fa-sign-in-alt"></i> Check-in
              </button>
            </form>
          @elseif($undangan->status_kehadiran === 'hadir')
            <span class="badge badge-success">Sudah Check-in</span>
            <small class="text-muted d-block">
              {{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i') : '' }}
            </small>
          @else
            <span class="badge badge-danger">Tidak Hadir</span>
          @endif
        </div>
      </div>

      @push('scripts')
      <script>
      function doCheckin{{ $rapat->id }}() {
        if (!navigator.geolocation) {
          alert('Perangkat tidak mendukung geolokasi.');
          return;
        }
        navigator.geolocation.getCurrentPosition(function(pos){
          const form = document.getElementById('checkinForm{{ $rapat->id }}');
          form.latitude.value = pos.coords.latitude.toFixed(6);
          form.longitude.value = pos.coords.longitude.toFixed(6);
          form.submit();
        }, function(err){
          alert('Gagal mengambil lokasi: ' + err.message);
        }, { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 });
      }
      </script>
      @endpush

    @empty
      <p class="text-muted">Anda belum memiliki undangan rapat.</p>
    @endforelse
  </div>
</div>
@endsection
