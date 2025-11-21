@extends('layouts.admin')

@section('title','Scan QR Survey Rapat Eksternal')
@section('page-title','Scan QR Survey Rapat Eksternal')

@section('content')
<div class="card shadow-sm">
  <div class="card-body text-center">
    <h5 class="mb-3">Arahkan kamera ke QR Code Survey Rapat</h5>
    <div id="readerSurvey" style="width:320px;margin:auto;"></div>
    <div id="scanResultSurvey" class="mt-3 text-info"></div>

    <a href="{{ route('tamu.rapat.saya') }}" class="btn btn-secondary mt-3">
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
    if (!decodedText.includes('/tamu/survey-rapat/eksternal/')) {
      alert("QR tidak valid untuk survey rapat eksternal.");
      return;
    }
    const parts = decodedText.split('/');
    const slug = parts[parts.length - 1];
    const processUrl = "{{ route('tamu.rapat.scan.survey.eksternal.process', $rapat->id) }}" + "?slug=" + encodeURIComponent(slug);
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
