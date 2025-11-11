@extends('layouts.app')

@section('title','Check-in Pending')

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
  background: radial-gradient(circle at 30% 40%, rgba(80,130,255,0.1), transparent 60%);
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
  background: rgba(20, 25, 40, 0.85);
  box-shadow: 0 0 30px rgba(100, 150, 255, 0.25),
              inset 0 0 20px rgba(120, 150, 255, 0.08);
  border: 1px solid rgba(130, 150, 255, 0.2);
  transition: all 0.4s ease;
  backdrop-filter: blur(10px);
}
.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 0 50px rgba(120, 150, 255, 0.4);
}

/* ==== ICON ==== */
.pending-icon {
  color: #facc15; /* kuning */
  text-shadow: 0 0 15px rgba(250,204,21,0.5);
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
  background: linear-gradient(90deg, #60a5fa, #93c5fd);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.lead {
  font-size: 15px;
  color: #cbd5e1;
}

/* ==== BUTTON ==== */
.btn-primary {
  background: linear-gradient(135deg, #5c6cff, #00d4ff);
  border: none;
  padding: 13px 32px;
  border-radius: 50px;
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #fff;
  box-shadow: 0 0 20px rgba(90, 140, 255, 0.3);
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
  box-shadow: 0 0 30px rgba(120,160,255,0.6);
}

/* ==== FLOATING PARTICLES DECOR ==== */
.floating-circle {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(100,150,255,0.15), transparent 70%);
  animation: floaty 8s infinite ease-in-out;
}
.floating-circle.one {
  width: 200px; height: 200px;
  top: 10%; left: 15%;
}
.floating-circle.two {
  width: 300px; height: 300px;
  bottom: 5%; right: 10%;
  animation-delay: 2s;
}
@keyframes floaty {
  0%, 100% { transform: translateY(0) scale(1); }
  50% { transform: translateY(-20px) scale(1.1); }
}
</style>

<div class="particles"></div>
<div class="floating-circle one"></div>
<div class="floating-circle two"></div>

<section class="page-section text-center">
  <div class="container">
    <div class="card border-0 mx-auto" style="max-width: 600px;" data-aos="zoom-in">
      <div class="card-body py-5 px-4">
        <div class="mb-4">
          <i class="fas fa-envelope fa-5x pending-icon"></i>
        </div>
        <h2 class="mb-3">Check-in Pending</h2>
        <p class="lead mb-4">
          Check-in Anda untuk rapat <strong>{{ $rapat->judul ?? 'Rapat' }}</strong> telah disubmit.<br>
          Silakan cek email <strong>{{ auth()->user()->email }}</strong> untuk verifikasi kehadiran.<br>
          Link verifikasi hanya berlaku sekali.
        </p>
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
