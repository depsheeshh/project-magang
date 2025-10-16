@extends('layouts.app')

@section('title', 'Form Buku Tamu')

@section('content')
<style>
/* 🌌 Background gradient */
body {
  background: radial-gradient(circle at top, #0d1b2a 0%, #000814 100%);
  color: #e0e6f1;
  font-family: 'Poppins', sans-serif;
}

/* ✨ Card utama */
.card {
  border: none;
  border-radius: 20px;
  background: rgba(13, 25, 48, 0.9);
  backdrop-filter: blur(16px);
  box-shadow: 0 8px 25px rgba(0, 145, 255, 0.15);
  overflow: hidden;
  transition: all 0.3s ease-in-out;
}

.card:hover {
  box-shadow: 0 12px 35px rgba(0, 170, 255, 0.25);
  transform: translateY(-3px);
}

/* Header card dengan efek gradient neon */
.card-header {
  background: linear-gradient(90deg, #0077ff, #00b4ff);
  color: #fff;
  border: none;
  text-align: center;
  font-weight: 600;
  letter-spacing: 0.5px;
  padding: 1.2rem 0;
  box-shadow: 0 4px 12px rgba(0, 132, 255, 0.3);
}

/* === Progress Bar === */
.progress {
  height: 10px;
  border-radius: 8px;
  background: rgba(255,255,255,0.1);
  margin-top: 12px;
  overflow: hidden;
}
.progress-bar {
  height: 100%;
  width: 0%;
  border-radius: 8px;
  background: linear-gradient(90deg, #00bfff, #0077ff);
  transition: width 0.4s ease-in-out;
}

/* === Label Form === */
.form-label {
  font-weight: 600;
  color: #b8c9e8;
}

/* === Input & Textarea === */
.form-control,
.form-select {
  border-radius: 10px;
  border: 1px solid rgba(0, 170, 255, 0.2);
  background: rgba(10, 20, 40, 0.7);
  color: #e8f1ff;
  transition: all 0.3s ease;
  box-shadow: inset 0 0 8px rgba(0, 60, 120, 0.1);
}

.form-control:focus,
.form-select:focus {
  border-color: #00bfff;
  box-shadow: 0 0 12px rgba(0, 200, 255, 0.5);
  background: rgba(15, 25, 55, 0.9);
  color: #fff;
}

/* === Tombol Navigasi === */
.btn {
  border-radius: 10px;
  font-weight: 600;
  padding: 10px 22px;
  transition: all 0.3s ease;
}

.btn-secondary {
  background: linear-gradient(135deg, #2b3a57, #1a263e);
  border: none;
  color: #b5c9e8;
}

.btn-secondary:hover {
  background: linear-gradient(135deg, #31446d, #25365b);
  color: #fff;
}

.btn-primary {
  background: linear-gradient(135deg, #00aaff, #0066ff);
  border: none;
  color: #fff;
  box-shadow: 0 0 15px rgba(0, 157, 255, 0.4);
}

.btn-primary:hover {
  background: linear-gradient(135deg, #00ccff, #0077ff);
  box-shadow: 0 0 25px rgba(0, 180, 255, 0.6);
  transform: translateY(-2px);
}

.btn-success {
  background: linear-gradient(135deg, #2dd36f, #1ea85b);
  border: none;
  box-shadow: 0 0 15px rgba(0, 255, 130, 0.3);
}

.btn-success:hover {
  background: linear-gradient(135deg, #36e07a, #1bc965);
  box-shadow: 0 0 25px rgba(0, 255, 130, 0.5);
  transform: translateY(-2px);
}

/* === Card Bidang Tujuan === */
.custom-radio-card {
  position: relative;
  border: 1px solid rgba(0, 120, 255, 0.15);
  border-radius: 18px;
  background: linear-gradient(160deg, rgba(15, 25, 45, 0.9), rgba(5, 10, 25, 0.8));
  padding: 14px;
  transition: all 0.3s ease;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.custom-radio-card:hover {
  transform: translateY(-3px);
  border-color: #00bfff;
  box-shadow: 0 0 25px rgba(0, 170, 255, 0.3);
}

.custom-radio-card input[type="radio"] {
  display: none;
}

.custom-radio-card input[type="radio"]:checked + .radio-content .radio-icon {
  background: linear-gradient(135deg, #00b4ff, #0077ff);
  color: #fff;
  box-shadow: 0 0 12px rgba(0, 150, 255, 0.5);
}

.custom-radio-card input[type="radio"]:checked + .radio-content .radio-title {
  color: #00bfff;
}

/* === Isi Card === */
.radio-content {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.radio-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: rgba(0, 120, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #00bfff;
  font-size: 20px;
  transition: all 0.3s ease;
}

.radio-title {
  font-weight: 600;
  font-size: 15px;
  color: #dce6f5;
}

.radio-desc {
  font-size: 13px;
  color: #a7b8d8;
  opacity: 0.9;
}

/* === Step Transisi === */
.form-step {
  display: none;
  animation: fadeInUp 0.5s ease forwards;
}

.form-step.active {
  display: block;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(15px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsif */
@media (max-width: 768px) {
  .card-body {
    padding: 2rem;
  }
}
</style>

<div class="container py-5">
  <div class="card shadow-lg border-0 rounded-4">
    <div class="card-header text-center">
      <h5 class="fw-bold text-light mb-0">Form Buku Tamu</h5>
      <small class="text-light opacity-75">Langkah <span id="step-indicator">1</span> dari 3</small>

      <!-- Progress Bar -->
      <div class="progress mt-3">
        <div class="progress-bar" id="progress-bar"></div>
      </div>
    </div>

    <div class="card-body p-5">
      <form id="multiStepForm" action="{{ route('tamu.store') }}" method="POST">
        @csrf

        {{-- STEP 1 --}}
        <div class="form-step active">
          <div class="row g-4 mb-3">
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-person"></i> Nama Lengkap</label>
              <input type="text" name="name" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-envelope"></i> Email</label>
              <input type="email" name="email" class="form-control form-control-lg" required>
            </div>
          </div>
          <div class="row g-4 mb-3">
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-building"></i> Instansi / Perusahaan</label>
              <input type="text" name="instansi" class="form-control form-control-lg" required>
            </div>
            <div class="col-md-6">
              <label class="form-label"><i class="bi bi-phone"></i> No HP</label>
              <input type="tel" name="no_hp" class="form-control form-control-lg" required>
            </div>
          </div>
        </div>

        {{-- STEP 2 --}}
        <div class="form-step">
          <div class="mb-4">
            <label class="form-label"><i class="bi bi-geo-alt"></i> Alamat</label>
            <input type="text" name="alamat" class="form-control form-control-lg" required>
          </div>
          <div class="mb-4">
            <label class="form-label"><i class="bi bi-chat-left-text"></i> Keperluan</label>
            <textarea name="keperluan" class="form-control form-control-lg" rows="3" required></textarea>
          </div>
        </div>

        {{-- STEP 3 --}}
        <div class="form-step">
          <div class="text-center mb-4">
            <h5 class="fw-bold text-light mb-1">Pilih Tujuan</h5>
            <small class="text-info">Langkah 3 dari 3</small>
          </div>

          <div class="row g-4 mb-5">
            <div class="col-12">
              <h6 class="text-light mb-3">Tujuan Bidang</h6>
              <div id="bidang-options" class="row g-3">
                @foreach($bidang as $b)
                @php
                  $icon = match(true) {
                    str_contains(strtolower($b->nama_bidang), 'kepegawaian') => 'bi-person-badge',
                    str_contains(strtolower($b->nama_bidang), 'umum') => 'bi-gear',
                    str_contains(strtolower($b->nama_bidang), 'keuangan') => 'bi-cash-stack',
                    str_contains(strtolower($b->nama_bidang), 'informasi') => 'bi-globe',
                    str_contains(strtolower($b->nama_bidang), 'statistik') => 'bi-bar-chart',
                    default => 'bi-building'
                  };
                @endphp
                <div class="col-md-4">
                  <label class="custom-radio-card w-100 text-start">
                    <input type="radio" name="bidang_id" value="{{ $b->id }}" required>
                    <div class="radio-content">
                      <div class="radio-icon"><i class="bi {{ $icon }}"></i></div>
                      <div>
                        <div class="radio-title">{{ $b->nama_bidang }}</div>
                        <div class="radio-desc">{{ Str::limit($b->deskripsi, 70) }}</div>
                      </div>
                    </div>
                  </label>
                </div>
                @endforeach
              </div>
            </div>

            <div class="col-12 mt-4">
              <h6 class="text-light mb-2">Tujuan Pegawai</h6>
              <select name="pegawai_id" id="pegawai" class="form-select form-select-lg" required>
                <option value="">Pilih Bidang Terlebih Dahulu</option>
              </select>
            </div>
          </div>
        </div>

        {{-- Tombol Navigasi --}}
        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-secondary" id="prevBtn">Sebelumnya</button>
          <button type="button" class="btn btn-primary" id="nextBtn">Selanjutnya</button>
          <button type="submit" class="btn btn-success d-none" id="submitBtn">Kirim Form</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('input[name="bidang_id"]').forEach(radio => {
  radio.addEventListener('change', function() {
    let bidangId = this.value;
    let pegawaiSelect = document.getElementById('pegawai');
    pegawaiSelect.innerHTML = '<option value="">-- Memuat data pegawai... --</option>';

    fetch(`/tamu/get-pegawai/${bidangId}`)
      .then(res => res.json())
      .then(data => {
        pegawaiSelect.innerHTML = '<option value="">-- Pilih Pegawai --</option>';
        data.forEach(p => {
          let option = document.createElement('option');
          option.value = p.id;
          option.textContent = p.user.name;
          pegawaiSelect.appendChild(option);
        });
      });
  });
});

const steps = document.querySelectorAll(".form-step");
const nextBtn = document.getElementById("nextBtn");
const prevBtn = document.getElementById("prevBtn");
const submitBtn = document.getElementById("submitBtn");
const stepIndicator = document.getElementById("step-indicator");
const progressBar = document.getElementById("progress-bar");
let currentStep = 0;

function showStep(step) {
  steps.forEach((s, i) => s.classList.toggle("active", i === step));
  stepIndicator.textContent = step + 1;
  const progress = ((step + 1) / steps.length) * 100;
  progressBar.style.width = progress + "%";
  prevBtn.style.display = step === 0 ? "none" : "inline-block";
  nextBtn.style.display = step === steps.length - 1 ? "none" : "inline-block";
  submitBtn.classList.toggle("d-none", step !== steps.length - 1);
}

nextBtn.addEventListener("click", () => {
  if (currentStep < steps.length - 1) currentStep++;
  showStep(currentStep);
});

prevBtn.addEventListener("click", () => {
  if (currentStep > 0) currentStep--;
  showStep(currentStep);
});

showStep(currentStep);
</script>
@endpush
