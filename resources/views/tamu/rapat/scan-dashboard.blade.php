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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  function onScanSuccess(decodedText) {
    if (!decodedText.includes('/tamu/rapat/')) {
        Swal.fire({ icon:'error', title:'QR Tidak Valid', text:'QR ini bukan untuk rapat eksternal.' });
        return;
    }

    let validateUrl = decodedText.replace(/\/checkin\/[^/]+$/, '/validate-instansi');
    document.getElementById("scanResultDashboard").innerText = "Memvalidasi undangan instansi...";

    fetch(validateUrl, { headers:{ 'Accept':'application/json' } })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          Swal.fire({
            icon:'success', title:'Instansi Diundang', text:'Anda bisa melanjutkan check-in.',
            timer:1500, showConfirmButton:false
          }).then(() => {
            let dashboardUrl = decodedText.replace(/\/checkin\/[^/]+$/, '/checkin-dashboard');
            window.location.href = dashboardUrl;
          });
        } else {
          // 🚨 Kalau validasi gagal, tetap redirect ke form dashboard
          Swal.fire({
            icon:'warning', title:'Validasi Gagal',
            text:data.message || 'Silakan pilih instansi di form.'
          }).then(() => {
            let dashboardUrl = decodedText.replace(/\/checkin\/[^/]+$/, '/checkin-dashboard');
            window.location.href = dashboardUrl;
          });
        }
      })
      .catch(() => {
        // 🚨 Kalau fetch error (mobile/CORS), jangan berhenti → redirect ke form dashboard
        Swal.fire({
          icon:'error', title:'Kesalahan',
          text:'Validasi QR gagal, lanjut ke form.'
        }).then(() => {
          let dashboardUrl = decodedText.replace(/\/checkin\/[^/]+$/, '/checkin-dashboard');
          window.location.href = dashboardUrl;
        });
      });
  }

  function onScanError(errorMessage) {
    console.warn("Scan error: ", errorMessage);
  }

  let scanner = new Html5QrcodeScanner("readerDashboard", { fps: 15, qrbox: 250 });
  scanner.render(onScanSuccess, onScanError);
});
</script>
@endpush
