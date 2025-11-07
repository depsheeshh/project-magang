@extends('layouts.admin')

@section('title','Scan QR Rapat')
@section('page-title','Scan QR Rapat')

@section('content')
<div class="card">
  <div class="card-body text-center">
    <h5 class="mb-3">Arahkan kamera ke QR Code rapat</h5>
    <div id="reader" style="width:320px;margin:auto;"></div>
    <p class="text-muted mt-3">Pastikan kamera menghadap QR code yang ditampilkan admin.</p>

    {{-- Form auto-submit setelah QR terbaca --}}
    <form id="checkinForm" method="POST" style="display:none;">
      @csrf
      <input type="hidden" name="latitude" id="lat">
      <input type="hidden" name="longitude" id="lon">
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    // Validasi: pastikan URL mengandung /pegawai/rapat/
    if (!decodedText.includes('/pegawai/rapat/')) {
        alert("QR tidak valid untuk rapat internal.");
        return;
    }

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(pos) {
            document.getElementById('lat').value = pos.coords.latitude;
            document.getElementById('lon').value = pos.coords.longitude;

            let form = document.getElementById('checkinForm');
            form.action = decodedText; // gunakan URL dari QR
            form.submit();
        }, function(err) {
            alert("Gagal mendapatkan lokasi: " + err.message);
        });
    } else {
        alert("Browser tidak mendukung geolocation.");
    }
}

function onScanError(errorMessage) {
    console.warn("Scan error: ", errorMessage);
}

let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 15, qrbox: 250 } // fps lebih rendah untuk stabilitas
);
html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>
@endpush
