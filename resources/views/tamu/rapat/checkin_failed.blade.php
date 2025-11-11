@extends('layouts.app')

@section('title','Check-in Gagal')

@section('content')
<style>
/* ==== GLOBAL ==== */
body {
  font-family: 'Poppins', sans-serif;
  background: radial-gradient(circle at 20% 20%, #0d1117, #0b1220 60%, #0a0e1a);
  color: #e2e8f0;
  overflow: hidden;
}

/* ==== BACKGROUND ANIMATION ==== */
.particles {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: radial-gradient(circle at 30% 40%, rgba(255,80,80,0.1), transparent 60%);
  animation: moveGlow 8s infinite alternate ease-in-out;
  z-index: 0;
}
@keyframes moveGlow {
  from { transform: translate(0, 0); opacity: 0.6; }
  to { transform: translate(50px, 40px); opacity: 0.9; }
}

/* ==== PAGE SECTION ==== */
.page-section {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  z-index: 2;
  padding: 50px 15px;
}

/* ==== CARD ==== */
.card {
  border-radius: 24px;
  background: rgba(40, 20, 20, 0.85);
  box-shadow: 0 0 30px rgba(255, 100, 100, 0.25),
              inset 0 0 20px rgba(255, 120, 120, 0.08);
  border: 1px solid rgba(255, 130, 130, 0.2);
  transition: all 0.4s ease;
  backdrop-filter: blur(10px);
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0 50px rgba(255, 120, 120, 0.4);
}

/* ==== ICON ==== */
.failed-icon {
  color: #ef4444; /* merah */
  text-shadow: 0 0 15px rgba(239,68,68,0.5);
  animation: popIn 0.6s ease-out forwards;
}
@keyframes popIn {
  from { transform: scale(0); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

/* ==== TEXT ==== */
h2 {
  font-weight: 700;
  letter-spacing: 1px;
  background: linear-gradient(90deg, #f87171, #fca5a5);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.lead {
  font-size: 15px;
  color: #fca5a5;
}

/* ==== BUTTON ==== */
.btn-primary {
  background: linear-gradient(135deg, #ff5c5c, #ff9c9c);
  border: none;
  padding: 13px 32px;
  border-radius: 50px;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #fff;
  box-shadow: 0 0 20px rgba(255, 90, 90, 0.3);
  transition: 0.3s ease;
  position: relative;
  overflow: hidden;
}
.btn-primary::before {
  content: "";
  position: absolute;
  top: 0; left: -100%;
  width: 100%; height: 100%;
  background: rgba(255,255,255,0.2);
  transition: all 0.4s ease;
}
.btn-primary:hover::before {
  left: 100%;
}
.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 0 30px rgba(255,120,120,0.6);
}
</style>

<div class="particles"></div>

<section class="page-section text-center">
  <div class="container">
    <div class="card border-0 mx-auto" style="max-width: 600px;" data-aos="zoom-in">
      <div class="card-body py-5 px-4">
        <div class="mb-4">
          <i class="fas fa-times-circle fa-5x failed-icon"></i>
        </div>
        <h2 class="mb-3">Check-in Gagal</h2>
        @if(session('error'))
        <p class="lead mb-4">{{ session('error') }}</p>
        @else
        <p class="lead mb-4">
            Link verifikasi Anda tidak valid atau sudah kadaluarsa.<br>
            Silakan lakukan check-in ulang untuk mendapatkan link verifikasi baru.
        </p>
        @endif
        <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
          <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
      </div>
    </div>
  </div>
</section>

{{-- AOS Animation Library --}}
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css" />
<script>
AOS.init({ duration: 800, once: true });
</script>
@endsection
