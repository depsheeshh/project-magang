@extends('layouts.admin')

@section('title','Scan QR Survey Rapat Internal')
@section('page-title','Scan QR Survey Rapat Internal')

@section('content')
<div class="card shadow-sm">
  <div class="card-body text-center">
    <h5 class="mb-3">Arahkan kamera ke QR Code Survey Rapat</h5>
    <div id="readerSurvey" style="width:320px;margin:auto;"></div>
    <div id="scanResultSurvey" class="mt-3 text-info"></div>

    <a href="{{ route('pegawai.rapat.detail',$rapat->id) }}" class="btn btn-secondary mt-3">
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
    // ✅ QR harus mengandung slug survey internal
    if (!decodedText.includes('/pegawai/survey-rapat/')) {
      alert("QR tidak valid untuk survey rapat internal.");
      return;
    }

    // Ambil slug dari decodedText
    let parts = decodedText.split('/');
    let slug = parts[parts.length - 1]; // ambil slug terakhir

    // 🔀 Redirect ke controller process (update status + arahkan ke form)
    let processUrl = "{{ route('pegawai.rapat.scanSurvey.process', $rapat->id) }}" + "?slug=" + slug;
    window.location.href = processUrl;
  }

  function onScanError(errorMessage) {
    console.warn("Scan error: ", errorMessage);
  }

  let scanner = new Html5QrcodeScanner("readerSurvey", { fps: 15, qrbox: 250 });
  scanner.render(onScanSuccess, onScanError);
});
</script>
@endpush
