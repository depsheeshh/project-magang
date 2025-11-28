@extends('layouts.apelpagi')

@section('title','Apel Pagi')

@push('style')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

@section('content')
<div class="card card-modern p-4">
  <h4 class="mb-3"><i class="fa-solid fa-id-card me-2"></i> Data Pegawai</h4>
  <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</p>
  <p><strong>NIP:</strong> {{ $pegawai->nip }}</p>
  <p><strong>Nama:</strong> {{ $pegawai->user->name }}</p>
  <p><strong>Bidang:</strong> {{ $pegawai->bidang->nama_bidang ?? '-' }}</p>
  <p><strong>Jabatan:</strong> {{ $pegawai->jabatan->nama_jabatan ?? '-' }}</p>

  {{-- Status jarak --}}
  <div id="distanceStatus" class="mt-2 fw-bold" style="display:none;"></div>

  @if(!$absen)
    {{-- ✅ pakai token, bukan NIP --}}
    <form action="{{ route('apelpagi.masuk',$pegawai->apel_token) }}" method="POST" id="formMasuk">
      @csrf
      <input type="hidden" name="latitude" id="lat">
      <input type="hidden" name="longitude" id="lon">
      <button type="submit" class="btn btn-success btn-lg mt-3" id="btnMasuk" disabled>
        <i class="fa-solid fa-door-open me-2"></i> MASUK
      </button>
    </form>
  @else
    <div class="alert alert-info mt-3">
      Anda sudah absen pada {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('d/m/Y H:i') }}
      ({{ ucfirst($absen->status) }})
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
  const formMasuk = document.getElementById('formMasuk');
  const btnMasuk = document.getElementById('btnMasuk');

  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(pos) {
      const lat = pos.coords.latitude;
      const lon = pos.coords.longitude;
      const accuracy = pos.coords.accuracy;

      document.getElementById('lat').value = lat;
      document.getElementById('lon').value = lon;
      btnMasuk.disabled = false;

      const kantorLat = -6.725961238379822;
      const kantorLon = 108.5391054937919;
      const jarak = hitungJarak(lat, lon, kantorLat, kantorLon);
      const jarakText = jarak < 1000 ? `${Math.round(jarak)} M` : `${(jarak/1000).toFixed(2)} KM`;

      const statusEl = document.getElementById('distanceStatus');
      statusEl.style.display = 'block';

      // ✅ indikator warna + animasi
      if (jarak <= 50) {
        statusEl.innerHTML = `<span class="text-success animate__animated animate__fadeIn">
          ✔ Dalam radius kantor
        </span> (±${jarakText}, akurasi ±${Math.round(accuracy)} M)`;
      } else {
        statusEl.innerHTML = `<span class="text-danger animate__animated animate__shakeX">
          ✘ Di luar radius kantor
        </span> (±${jarakText}, akurasi ±${Math.round(accuracy)} M)`;
      }
    }, function(error) {
      alert("Gagal mendeteksi lokasi. Pastikan izin lokasi aktif.");
    }, {
      enableHighAccuracy: true,
      timeout: 10000,
      maximumAge: 0
    });
  } else {
    alert("Browser Anda tidak mendukung geolokasi.");
  }

  function hitungJarak(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const a = Math.sin(dLat/2) ** 2 +
              Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
              Math.sin(dLon/2) ** 2;
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
  }

  function toRad(deg) {
    return deg * Math.PI / 180;
  }

  // ✅ Auto-disable tombol setelah klik
  if (formMasuk) {
    formMasuk.addEventListener('submit', function() {
      btnMasuk.disabled = true;
      btnMasuk.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Memproses...';
    });
  }
});

@if(session('swal'))
Swal.fire({
  icon: '{{ session('swal.icon') }}',
  title: '{{ session('swal.title') }}',
  text: '{{ session('swal.text') }}',
});
@endif
</script>
@endpush
