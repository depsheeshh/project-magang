@extends('layouts.app')

@section('title', 'Scan QR Rapat Eksternal')

@section('content')
<style>
  body {
    background: linear-gradient(180deg, #020617, #0f172a);
    min-height: 100vh;
    color: #e2e8f0;
  }

  .scan-container {
    margin-top: 120px; /* Tambah margin biar gak ketutupan navbar */
    text-align: center;
  }

  .scan-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 0 25px rgba(59, 130, 246, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s ease;
  }
  .scan-card:hover {
    box-shadow: 0 0 40px rgba(59, 130, 246, 0.4);
    transform: translateY(-3px);
  }

  #reader {
    width: 100%;
    max-width: 420px;
    margin: 0 auto;
    border-radius: 15px;
    overflow: hidden;
  }

  .scan-title {
    font-weight: 700;
    color: #93c5fd;
    letter-spacing: 1px;
    margin-bottom: 20px;
  }

  #scan-result {
    color: #38bdf8;
    font-size: 1.1rem;
    margin-top: 15px;
  }

  .btn-back-glow {
  position: relative;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  letter-spacing: 0.5px;
  border: none;
  border-radius: 50px;
  padding: 12px 28px;
  color: #fff;
  background: linear-gradient(135deg, #1e293b, #0f172a);
  box-shadow: 0 0 12px rgba(59,130,246,0.3);
  overflow: hidden;
  transition: all 0.35s ease;
  cursor: pointer;
  z-index: 1;
}

/* Cahaya bergerak di tengah tombol */
.btn-back-glow::before {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(
    120deg,
    rgba(59,130,246,0.2),
    rgba(56,189,248,0.3),
    rgba(147,197,253,0.15)
  );
  background-size: 300% 300%;
  border-radius: 50px;
  filter: blur(6px);
  opacity: 0.7;
  z-index: -1;
  transition: 0.5s;
  animation: lightFlow 6s linear infinite;
}

/* Border efek neon */
.btn-back-glow::after {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: 50px;
  border: 2px solid rgba(147,197,253,0.4);
  box-shadow: 0 0 10px rgba(59,130,246,0.5);
  opacity: 0.6;
  z-index: -1;
  transition: 0.4s ease;
}

/* Efek hover */
.btn-back-glow:hover {
  transform: translateY(-3px) scale(1.03);
  box-shadow: 0 0 25px rgba(56,189,248,0.5);
}
.btn-back-glow:hover::after {
  box-shadow: 0 0 35px rgba(147,197,253,0.7);
  border-color: rgba(147,197,253,0.8);
}

/* Ikon bergerak halus */
.btn-back-glow i {
  transition: transform 0.3s ease;
}
.btn-back-glow:hover i {
  transform: translateX(-4px);
}

@keyframes lightFlow {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}
</style>

<div class="container scan-container mb-5">
  <div class="scan-card mx-auto" style="max-width: 500px;">
    <h3 class="scan-title">
      <i class="fas fa-qrcode me-2"></i> Scan QR Code Rapat Eksternal
    </h3>

    <div id="reader"></div>

    <div id="scan-result" class="mt-3 text-center"></div>

    <div class="mt-4 d-flex gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="400">
      <button class="btn-back-glow" onclick="window.history.back()">
        Kembali
        </button>
    </div>
  </div>
</div>

{{-- Script html5-qrcode --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const readerElem = document.getElementById("reader");
  const resultElem = document.getElementById("scan-result");

  function onScanSuccess(decodedText, decodedResult) {
    resultElem.innerHTML = `
      <p><strong>QR Terdeteksi:</strong> ${decodedText}</p>
      <p class="text-info">Mengarahkan ke halaman...</p>
    `;
    setTimeout(() => {
      window.location.href = decodedText;
    }, 1200);
  }

  function onScanFailure(error) {
    console.warn(`Scan gagal: ${error}`);
  }

  const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
      fps: 10,
      qrbox: 250,
      rememberLastUsedCamera: true,
      showTorchButtonIfSupported: true
    },
    false
  );

  html5QrcodeScanner.render(onScanSuccess, onScanFailure);
});
</script>
@endsection
