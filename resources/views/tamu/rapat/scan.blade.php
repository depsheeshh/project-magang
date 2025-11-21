@extends('layouts.scan-layout')

@section('title', 'Scan QR Rapat Eksternal')
@section('page-title','Scan QR Rapat Eksternal')

@section('content')
<style>
  body {
    background: linear-gradient(180deg, #0726b0, #0f172a);
    min-height: 100vh;
    color: #e2e8f0;
  }
  .scan-container { margin-top: 100px; text-align: center; }
  .scan-card {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 0 25px rgba(59,130,246,0.2);
    border: 1px solid rgba(255,255,255,0.08);
    max-width: 520px;
    margin: auto;
  }
  .scan-title { font-weight: 700; color: #93c5fd; margin-bottom: 20px; }
  #readerExternal { width:100%; max-width:420px; margin:auto; border-radius:15px; overflow:hidden; }
  #scanResultExternal { color:#38bdf8; font-size:1.1rem; margin-top:15px; }
</style>

<div class="container scan-container mb-5">
  <div class="scan-card">
    <h3 class="scan-title" id="scanTitleExternal">
      <i class="fas fa-qrcode me-2"></i> Scan QR Code Rapat Eksternal
    </h3>

    <div id="readerExternal"></div>
    <div id="scanResultExternal" class="mt-3 text-center"></div>

    <div class="mt-4">
      <button class="btn btn-secondary" onclick="window.history.back()">Kembali</button>
    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const resultElem = document.getElementById("scanResultExternal");

  function onScanSuccess(decodedText) {
    let urlObj = new URL(decodedText, window.location.origin);
    let pathOnly = urlObj.pathname + urlObj.search;

    resultElem.innerHTML = `<p><strong>QR Terdeteksi:</strong> ${decodedText}</p><p class="text-info">Mengarahkan...</p>`;

    setTimeout(() => {
      // ✅ hanya untuk check-in rapat eksternal
      if (!decodedText.includes('/tamu/rapat/')) {
        alert("QR tidak valid untuk rapat eksternal.");
        return;
      }
      window.location.href = window.location.origin + pathOnly;
    }, 1200);
  }

  function onScanFailure(error) {
    console.warn("Scan gagal: ", error);
  }

  const scanner = new Html5QrcodeScanner("readerExternal", {
    fps: 10, qrbox: 250, rememberLastUsedCamera: true, showTorchButtonIfSupported: true
  }, false);
  scanner.render(onScanSuccess, onScanFailure);
});
</script>
@endsection
