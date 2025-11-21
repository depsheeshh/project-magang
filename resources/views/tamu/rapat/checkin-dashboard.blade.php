@extends('layouts.admin')

@section('title','Check-in Rapat Eksternal')
@section('page-title','Check-in Rapat Eksternal')

@section('content')
<div class="card shadow-sm">
  <div class="card-body">
    <form action="{{ route('tamu.rapat.checkin.submit.dashboard',$rapat->id) }}" method="POST" id="checkinForm">
      @csrf
      <div class="form-group mb-3">
        <label>Nama</label>
        <input type="text" class="form-control" value="{{ $user->name }}" readonly>
      </div>
      <div class="form-group mb-3">
        <label>Email</label>
        <input type="text" class="form-control" value="{{ $user->email }}" readonly>
      </div>

      <div class="form-group mb-3">
        <label for="instansi_id"><i class="bi bi-building"></i> Instansi (opsional)</label>
        <select name="instansi_id" id="instansi_id" class="form-control">
          <option value="">-- Pilih Instansi --</option>
          @foreach($instansiList as $undangan)
            @php
              $kuota = $undangan->kuota;
              $hadir = $undangan->jumlah_hadir;
              $sisa  = max(0, $kuota - $hadir);
              $penuh = $sisa <= 0;
            @endphp
            <option value="{{ $undangan->instansi->id }}"
                    @if($penuh) disabled class="text-muted" @endif>
              {{ $undangan->instansi->nama_instansi }}
              (Kuota: {{ $kuota }}, Hadir: {{ $hadir }}, Sisa: {{ $sisa }})
              @if($penuh) - [Penuh] @endif
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group mb-3">
        <label for="jabatan"><i class="bi bi-briefcase"></i> Jabatan (opsional)</label>
        <input type="text" name="jabatan" id="jabatan" class="form-control"
               placeholder="Contoh: Kepala Seksi, Staf, dll">
      </div>

      {{-- Hidden field untuk geofencing --}}
      <input type="hidden" name="latitude" id="latitude">
      <input type="hidden" name="longitude" id="longitude">

      <button type="submit" class="btn btn-primary mt-3">
        <i class="fas fa-check"></i> Konfirmasi Check-in
      </button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
      document.getElementById('latitude').value = pos.coords.latitude;
      document.getElementById('longitude').value = pos.coords.longitude;
    }, function() {
      alert("Aktifkan GPS agar lokasi Anda terdeteksi.");
    });
  } else {
    alert("Browser tidak mendukung geolocation.");
  }
});
</script>
@endpush
