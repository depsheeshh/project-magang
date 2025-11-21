@extends('layouts.admin')

@section('title','Scan QR Survey Rapat Eksternal')
@section('page-title','Scan QR Survey Rapat Eksternal')

@section('content')
<style>
    .btn-gradient-primary {
    background: linear-gradient(45deg, #007bff, #00c6ff);
    color: #fff;
    border: none;
    transition: all 0.3s ease;
    }
    .btn-gradient-primary:hover {
    background: linear-gradient(45deg, #0056b3, #0099cc);
    transform: scale(1.05);
    }
    .bg-gradient-primary {
    background: linear-gradient(90deg, #007bff, #00c6ff);
    }
</style>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12">
      <div class="card shadow-lg border-0 rounded">
        <div class="card-header bg-gradient-primary text-white text-center">
          <h4 class="mb-0">
            <i class="fas fa-poll"></i> Survey Rapat Eksternal
          </h4>
        </div>
        <div class="card-body text-center">
          <h5 class="mb-3">{{ $survey->judul }}</h5>
          <p class="text-muted">{{ $survey->deskripsi ?? 'Tidak ada deskripsi' }}</p>

          {{-- QR Scanner --}}
          <div id="readerEksternal" style="width:100%;max-width:350px;margin:auto;"></div>
          <div id="scanResultEksternal" class="mt-3 text-info"></div>

          {{-- Tombol alternatif --}}
          {{-- <div class="mt-4">
            <a href="{{ route('tamu.survey.rapat.form.eksternal', $survey->slug) }}"
               class="btn btn-gradient-primary btn-block shadow-sm">
              <i class="fas fa-qrcode"></i> Scan / Isi Survey
            </a>
          </div> --}}
        </div>
        <div class="card-footer text-center bg-light">
          <small class="text-muted">
            Survey ini digunakan untuk rapat eksternal berjudul <strong>{{ $rapat->judul }}</strong>.
          </small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  function onScanSuccess(decodedText) {
    if (!decodedText.includes('/tamu/survey-rapat/')) {
      alert("QR tidak valid untuk survey rapat eksternal.");
      return;
    }
    window.location.href = decodedText;
  }

  function onScanError(errorMessage) {
    console.warn("Scan error: ", errorMessage);
  }

  let scanner = new Html5QrcodeScanner("readerEksternal", { fps: 15, qrbox: 250 });
  scanner.render(onScanSuccess, onScanError);
});
</script>
@endpush
