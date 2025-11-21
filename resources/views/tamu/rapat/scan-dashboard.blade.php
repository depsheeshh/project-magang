@extends('layouts.admin')

@section('title','Scan QR Rapat Eksternal')
@section('page-title','Scan QR Rapat Eksternal')

@section('content')
<div class="card shadow-sm">
  <div class="card-body text-center">
    <h5 class="mb-3">Arahkan kamera ke QR Code Rapat Eksternal</h5>
    <div id="readerDashboard" style="width:320px;margin:auto;"></div>
    <div id="scanResultDashboard" class="mt-3 text-info"></div>
    <a href="{{ route('tamu.rapat.saya') }}" class="btn btn-secondary mx-1">
          <i class="fas fa-arrow-left"></i> Kembali
      </a>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  function onScanSuccess(decodedText) {
    if (!decodedText.includes('/tamu/rapat/')) {
      alert("QR tidak valid untuk rapat eksternal.");
      return;
    }

    // Ganti segment checkin/{token} → checkin-dashboard
    let dashboardUrl = decodedText.replace(/\/checkin\/[^/]+$/, '/checkin-dashboard');

    document.getElementById("scanResultDashboard").innerText = "QR valid, membuka form...";
    window.location.href = dashboardUrl;
  }

  function onScanError(errorMessage) {
    console.warn("Scan error: ", errorMessage);
  }

  let scanner = new Html5QrcodeScanner("readerDashboard", { fps: 15, qrbox: 250 });
  scanner.render(onScanSuccess, onScanError);
});
</script>
@endpush
