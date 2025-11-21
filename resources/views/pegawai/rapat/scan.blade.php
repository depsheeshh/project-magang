@extends('layouts.admin')

@section('title','Scan QR Rapat Internal')
@section('page-title','Scan QR Rapat Internal')

@section('content')
<div class="card shadow-sm">
  <div class="card-body text-center">
    <h5 class="mb-3" id="scanTitleInternal">Arahkan kamera ke QR Code Rapat</h5>
    <div id="readerInternal" style="width:320px;margin:auto;"></div>
    <div id="scanResultInternal" class="mt-3 text-info"></div>

    {{-- Form auto-submit untuk check-in rapat --}}
    <form id="checkinFormInternal" method="POST" style="display:none;">
      @csrf
      <input type="hidden" name="latitude" id="latInternal">
      <input type="hidden" name="longitude" id="lonInternal">
    </form>
    <a href="{{ route('pegawai.agenda.rapat') }}" class="btn btn-secondary mx-1">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const scanTitle = document.getElementById("scanTitleInternal");
  const resultElem = document.getElementById("scanResultInternal");

  function onScanSuccess(decodedText) {
    let urlObj = new URL(decodedText, window.location.origin);
    let pathOnly = urlObj.pathname + urlObj.search;

    // ✅ Hanya valid untuk QR rapat internal
    if (!decodedText.includes('/pegawai/rapat/')) {
      alert("QR tidak valid untuk rapat internal.");
      return;
    }

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(pos) {
        document.getElementById('latInternal').value = pos.coords.latitude;
        document.getElementById('lonInternal').value = pos.coords.longitude;
        let form = document.getElementById('checkinFormInternal');
        form.action = window.location.origin + pathOnly;
        form.submit();
      }, function(err) {
        alert("Gagal mendapatkan lokasi: " + err.message);
      });
    }
  }

  function onScanError(errorMessage) {
    console.warn("Scan error: ", errorMessage);
  }

  let scanner = new Html5QrcodeScanner("readerInternal", { fps: 15, qrbox: 250 });
  scanner.render(onScanSuccess, onScanError);
});
</script>
@endpush
