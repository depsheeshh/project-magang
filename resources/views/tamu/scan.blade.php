@extends('layouts.app')

@section('title', 'Scan QR Code')

@section('content')
<style>
/* ==== GLOBAL THEME ==== */
body {
  background: radial-gradient(circle at 20% 20%, #0d1117, #0b1220 60%, #0a0e1a);
  color: #e2e8f0;
  font-family: 'Poppins', sans-serif;
  overflow-x: hidden;
}

/* ==== BACKGROUND PARTICLES ==== */
.particles {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  pointer-events: none;
  background: radial-gradient(circle at 20% 20%, rgba(60,80,255,0.05), transparent 70%);
  animation: glowMove 10s infinite alternate ease-in-out;
  z-index: 0;
}

@keyframes glowMove {
  0% { background-position: 0 0; }
  100% { background-position: 100px 100px; }
}

/* ==== PAGE SECTION ==== */
.page-section {
  position: relative;
  z-index: 2;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding-top: 100px;
  padding-bottom: 100px;
}

/* ==== HEADER TEXT ==== */
h2 {
  background: linear-gradient(90deg, #5c6cff, #00e0ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  font-weight: 700;
  letter-spacing: 1px;
  font-size: 2rem;
}

.text-muted {
  color: #a1a1aa !important;
}

/* ==== SCANNER BOX ==== */
#reader {
  width: 340px;
  max-width: 90%;
  border-radius: 20px;
  overflow: hidden;
  background: rgba(15, 20, 40, 0.85);
  border: 1px solid rgba(100, 120, 255, 0.3);
  box-shadow: 0 0 25px rgba(100, 150, 255, 0.25), inset 0 0 30px rgba(100, 150, 255, 0.08);
  transition: all 0.4s ease;
  margin: 0 auto;
  backdrop-filter: blur(8px);
}

#reader:hover {
  transform: scale(1.03);
  box-shadow: 0 0 40px rgba(120, 160, 255, 0.5);
}

/* ==== BUTTONS ==== */
.btn-modern {
  padding: 12px 28px;
  border-radius: 50px;
  font-weight: 600;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.btn-modern::before {
  content: "";
  position: absolute;
  top: 0; left: -100%;
  width: 100%; height: 100%;
  background: rgba(255,255,255,0.15);
  transition: all 0.4s ease;
}
.btn-modern:hover::before {
  left: 100%;
}

.btn-primary {
  background: linear-gradient(135deg, #5c6cff, #00d4ff);
  border: none;
  color: #fff;
  box-shadow: 0 0 15px rgba(90, 140, 255, 0.4);
}
.btn-primary:hover {
  background: linear-gradient(135deg, #6f7dff, #2ce3ff);
  box-shadow: 0 0 25px rgba(120, 180, 255, 0.6);
  transform: translateY(-3px);
}

.btn-outline-secondary {
  border: 2px solid #6b7280;
  color: #d1d5db;
}
.btn-outline-secondary:hover {
  background: rgba(255,255,255,0.1);
  color: #fff;
  transform: translateY(-3px);
}

/* ==== ANIMATED FRAME ==== */
.frame-glow {
  position: absolute;
  top: -15px; left: -15px; right: -15px; bottom: -15px;
  border-radius: 25px;
  background: linear-gradient(45deg, rgba(0,150,255,0.4), rgba(255,0,255,0.2));
  filter: blur(15px);
  z-index: -1;
  animation: framePulse 3s infinite alternate;
}
@keyframes framePulse {
  from { opacity: 0.3; transform: scale(0.98); }
  to { opacity: 0.6; transform: scale(1.02); }
}
</style>

<div class="particles"></div>

<section class="page-section" data-aos="zoom-in">
  <div class="container">
    <h2 data-aos="fade-down" data-aos-delay="100">🔍 Scan QR Code</h2>
    <p class="text-muted mb-4" data-aos="fade-up" data-aos-delay="200">
      Arahkan kamera Anda ke QR Code untuk memulai kunjungan.
    </p>

    <div class="position-relative d-inline-block" data-aos="zoom-in" data-aos-delay="300">
      <div class="frame-glow"></div>
      <div id="reader"></div>
    </div>

    <div class="mt-4 d-flex gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="400">
      <button class="btn btn-primary btn-modern" id="flash-btn">
        <i class="bi bi-lightning-charge"></i> Nyalakan Flash
      </button>
      <button class="btn btn-outline-secondary btn-modern" onclick="window.history.back()">
        <i class="bi bi-x-circle"></i> Batal
      </button>
    </div>
  </div>
</section>

{{-- ==== SCRIPT ==== --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
<script>
AOS.init({ duration: 800, once: true });

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

let scanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 220 });
scanner.render(onScanSuccess);
</script>
@endsection
