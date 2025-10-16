@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<style>
body {
  background: radial-gradient(circle at top, #0d1117, #121826);
  color: #fff;
}

.page-section {
  padding-top: 100px;
  padding-bottom: 100px;
  min-height: 100vh;
}

#reader {
  border-radius: 20px;
  overflow: hidden;
  background: #1c2232;
  box-shadow: 0 0 25px rgba(100, 120, 255, 0.3), inset 0 0 20px rgba(100, 120, 255, 0.1);
  border: 1px solid rgba(130, 150, 255, 0.2);
  transition: all 0.3s ease;
  margin: 0 auto;
}
#reader:hover {
  transform: scale(1.03);
  box-shadow: 0 0 40px rgba(130, 150, 255, 0.5);
}

.btn-modern {
  padding: 12px 26px;
  border-radius: 50px;
  font-weight: 600;
  transition: all 0.3s ease;
}
.btn-modern:hover {
  transform: translateY(-3px);
}

.btn-primary {
  background: linear-gradient(135deg, #5c6cff, #9b8cff);
  border: none;
  box-shadow: 0 0 15px rgba(120, 140, 255, 0.4);
}
.btn-primary:hover {
  background: linear-gradient(135deg, #6c7aff, #b1a6ff);
  box-shadow: 0 0 25px rgba(120, 140, 255, 0.6);
}

.btn-outline-secondary {
  border: 2px solid #aaa;
  color: #eee;
}
.btn-outline-secondary:hover {
  background: #2a324a;
  color: #fff;
}
</style>

<section class="page-section d-flex flex-column justify-content-center align-items-center text-center">
  <div class="container text-center" data-aos="fade-up">
    <h2 class="fw-bold text-light mb-3" data-aos="fade-down">📷 Scan QR Code</h2>
    <p class="text-muted mb-4" data-aos="fade-up">Arahkan kamera Anda ke QR Code untuk memulai kunjungan</p>

    <div id="reader" style="width: 340px; max-width: 100%;" data-aos="zoom-in"></div>

    <div class="mt-4 d-flex gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="300">
        <button class="btn btn-primary btn-modern" id="flash-btn">
          <i class="bi bi-lightning-charge"></i> Flash
        </button>
        <button class="btn btn-outline-secondary btn-modern" onclick="window.history.back()">
          <i class="bi bi-x-circle"></i> Batal
        </button>
    </div>
  </div>
</section>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function onScanSuccess(decodedText) {
  fetch("{{ route('tamu.scan.success') }}", {
    method: "POST",
    headers: {
      "X-CSRF-TOKEN": "{{ csrf_token() }}",
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ qr: decodedText })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === "ok") {
      window.location.href = "{{ route('tamu.form') }}";
    }
  })
  .catch(err => alert("Terjadi kesalahan: " + err.message));
}

let scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 200 });
scanner.render(onScanSuccess);
</script>
@endsection
