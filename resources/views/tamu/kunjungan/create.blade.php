@extends('layouts.admin')

@section('title','Tambah Kunjungan')
@section('page-title','Form Tambah Kunjungan')

@push('style')
<style>
/* ======================================
   CARD PROFILE (Dark Mode Default)
   ====================================== */
.card-profile {
  border: none;
  border-radius: 18px;
  background: linear-gradient(145deg, #1b1b2f, #1e2743);
  color: #e0e8ff;
  box-shadow: 0 8px 20px rgba(0, 120, 255, 0.15);
  overflow: hidden;
  transition: all 0.3s ease;
}
.card-profile:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0, 150, 255, 0.25);
}

/* ======================================
   HEADER (Match dengan card-visit)
   ====================================== */
.card-header-profile {
  background: linear-gradient(90deg, #0077ff, #00b4ff);
  color: #fff;
  padding: 1.3rem;
  display: flex;
  align-items: center;
  gap: 12px;
  border: none;
  box-shadow: 0 3px 10px rgba(0,132,255,0.3);
}
.card-header-profile i {
  font-size: 2.3rem;
}
.card-header-profile h5 {
  font-weight: 600;
  margin: 0;
  letter-spacing: 0.4px;
}

/* ======================================
   INPUT FIELDS — Dark Mode Default
   ====================================== */
.form-control,
textarea.form-control,
select.form-control {
  background: rgba(20, 30, 55, 0.85);
  border: 1px solid rgba(0, 150, 255, 0.2);
  color: #e0e8ff;
  border-radius: 12px;
  transition: all 0.3s ease;
}
.form-control:focus,
textarea.form-control:focus,
select.form-control:focus {
  border-color: #00bfff;
  background: rgba(25, 35, 60, 0.95);
  color: #e0e8ff;
  box-shadow: 0 0 10px rgba(0,180,255,0.3);
}
label {
  color: #d0ddff;
}

/* Floating Label Adjust */
.form-floating > label {
  color: #c8d9ff !important;
}

/* ======================================
   SECTION TITLE (Match card-visit)
   ====================================== */
.text-section {
  border-left: 4px solid #00bfff;
  padding-left: 10px;
  margin-top: 30px;
  margin-bottom: 20px;
  font-weight: 600;
  color: #d0ddff;
}

/* ======================================
   BUTTON SIMPAN (Match card-visit)
   ====================================== */
.btn-save {
  background: linear-gradient(135deg, #00aaff, #0077ff);
  border: none;
  color: #fff;
  font-weight: 600;
  border-radius: 12px;
  padding: 12px 26px;
  box-shadow: 0 0 15px rgba(0, 157, 255, 0.3);
  transition: all 0.3s ease;
}
.btn-save:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(0, 180, 255, 0.5);
}

/* Fade Animation */
.card-body {
  animation: fadeInUp 0.6s ease;
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ======================================
   LIGHT MODE OVERRIDE
   ====================================== */
body:not(.dark-mode) .card-profile {
  background: #ffffff;
  color: #212529;
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}
body:not(.dark-mode) .card-header-profile {
  background: linear-gradient(90deg, #4da3ff, #74d4ff);
  color: #fff;
}
body:not(.dark-mode) label {
  color: #212529 !important;
}
body:not(.dark-mode) .form-control,
body:not(.dark-mode) textarea.form-control,
body:not(.dark-mode) select.form-control {
  background: #fff;
  border: 1px solid #ced4da;
  color: #212529;
}
body:not(.dark-mode) .form-control:focus {
  border-color: #00bfff;
  box-shadow: 0 0 0 0.2rem rgba(0,180,255,0.25);
}
body:not(.dark-mode) .text-section {
  color: #212529;
  border-left-color: #00bfff;
}
body:not(.dark-mode) .btn-save {
  background: linear-gradient(135deg, #2db7ff, #0077ff);
}
/* Wrapper card untuk radio */
.custom-radio-card {
  display: inline-block;
  cursor: pointer;
  border-radius: 12px;
  border: 1px solid rgba(0, 150, 255, 0.25);
  background: rgba(20, 30, 55, 0.85);
  padding: 14px 18px;
  transition: all 0.3s ease;
  min-width: 240px;
  max-width: 280px;
}

.custom-radio-card:hover {
  transform: translateY(-3px);
  border-color: #00bfff;
  box-shadow: 0 0 18px rgba(0,180,255,0.35);
}

/* Hide default radio */
.custom-radio-card input[type="radio"] {
  display: none;
}

/* Konten radio */
.radio-content {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}

.radio-icon {
  width: 48px;
  height: 48px;
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
  color: #e2ecff;
  margin-bottom: 4px;
}

.radio-desc {
  font-size: 13px;
  color: #a9bbd9;
}

/* Saat dipilih */
.custom-radio-card input[type="radio"]:checked + .radio-content .radio-icon {
  background: linear-gradient(135deg, #00b4ff, #0077ff);
  color: #fff;
  box-shadow: 0 0 12px rgba(0,150,255,0.5);
}

.custom-radio-card input[type="radio"]:checked + .radio-content .radio-title {
  color: #00bfff;
}
/* ======================================
   LIGHT MODE OVERRIDE untuk Radio Bidang
   ====================================== */
body:not(.dark-mode) .custom-radio-card {
  background: #f8f9fc;
  border: 1px solid #cce5ff;
  color: #212529;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
body:not(.dark-mode) .custom-radio-card:hover {
  border-color: #00bfff;
  box-shadow: 0 0 18px rgba(0,180,255,0.25);
}
body:not(.dark-mode) .radio-icon {
  background: rgba(0, 120, 255, 0.1);
  color: #007bff;
}
body:not(.dark-mode) .custom-radio-card input[type="radio"]:checked + .radio-content .radio-icon {
  background: linear-gradient(135deg, #00b4ff, #0077ff);
  color: #fff;
  box-shadow: 0 0 12px rgba(0,150,255,0.5);
}
body:not(.dark-mode) .custom-radio-card input[type="radio"]:checked + .radio-content .radio-title {
  color: #007bff;
}
body:not(.dark-mode) .radio-title {
  color: #212529;
}
body:not(.dark-mode) .radio-desc {
  color: #6c757d;
}

</style>
@endpush

@section('content')
<div class="card card-visit">
  <div class="card-header">
    <i class="fas fa-user-plus me-2"></i> Tambah Kunjungan Baru
  </div>

  <div class="card-body p-4">
    <form action="{{ route('tamu.kunjungan.store') }}" method="POST">
      @csrf

      {{-- Data Profil Tamu --}}
      <div class="mb-3">
        <label class="fw-semibold text-light">Instansi</label>
        <input type="text" class="form-control"
               value="{{ auth()->user()->tamu->instansi ?? '-' }}" readonly>
      </div>

      <div class="mb-3">
        <label class="fw-semibold text-light">No HP</label>
        <input type="text" class="form-control"
               value="{{ auth()->user()->tamu->no_hp ?? '-' }}" readonly>
      </div>

      <div class="mb-3">
        <label class="fw-semibold text-light">Alamat</label>
        <textarea class="form-control" rows="2" readonly>{{ auth()->user()->tamu->alamat ?? '-' }}</textarea>
      </div>

      <hr class="border-secondary my-4">

      {{-- Pilihan Bidang --}}
      <div class="form-group mb-4">
        <label class="fw-semibold text-light mb-3">Pilih Bidang Tujuan</label>
        <div id="bidang-options" class="d-flex flex-wrap justify-content-start">

          {{-- Sekretariat --}}
          <label class="custom-radio-card m-2">
            <input type="radio" name="bidang_id" value="1" required>
            <div class="radio-content">
              <div class="radio-icon"><i class="fas fa-envelope-open-text"></i></div>
              <div>
                <div class="radio-title">Sekretariat</div>
                <div class="radio-desc">Mengelola administrasi, SDM, keuangan & arsip</div>
              </div>
            </div>
          </label>

          {{-- Infrastruktur TIK --}}
          <label class="custom-radio-card m-2">
            <input type="radio" name="bidang_id" value="2" required>
            <div class="radio-content">
              <div class="radio-icon"><i class="fas fa-network-wired"></i></div>
              <div>
                <div class="radio-title">Infrastruktur TIK</div>
                <div class="radio-desc">Mengelola jaringan, server & sistem informatika</div>
              </div>
            </div>
          </label>

          {{-- Layanan E-Government --}}
          <label class="custom-radio-card m-2">
            <input type="radio" name="bidang_id" value="3" required>
            <div class="radio-content">
              <div class="radio-icon"><i class="fas fa-laptop-code"></i></div>
              <div>
                <div class="radio-title">E-Government</div>
                <div class="radio-desc">Mengembangkan aplikasi & layanan digital</div>
              </div>
            </div>
          </label>

          {{-- Informasi & Komunikasi Publik --}}
          <label class="custom-radio-card m-2">
            <input type="radio" name="bidang_id" value="4" required>
            <div class="radio-content">
              <div class="radio-icon"><i class="fas fa-bullhorn"></i></div>
              <div>
                <div class="radio-title">Informasi Publik</div>
                <div class="radio-desc">Mengelola informasi publik & komunikasi masyarakat</div>
              </div>
            </div>
          </label>

          {{-- Persandian & Keamanan Informasi --}}
          <label class="custom-radio-card m-2">
            <input type="radio" name="bidang_id" value="5" required>
            <div class="radio-content">
              <div class="radio-icon"><i class="fas fa-shield-alt"></i></div>
              <div>
                <div class="radio-title">Keamanan Informasi</div>
                <div class="radio-desc">Menangani persandian & keamanan data</div>
              </div>
            </div>
          </label>

          {{-- Statistik Sektoral --}}
          <label class="custom-radio-card m-2">
            <input type="radio" name="bidang_id" value="6" required>
            <div class="radio-content">
              <div class="radio-icon"><i class="fas fa-chart-bar"></i></div>
              <div>
                <div class="radio-title">Statistik Sektoral</div>
                <div class="radio-desc">Mengolah & menyajikan data statistik sektoral</div>
              </div>
            </div>
          </label>

        </div>
      </div>

      {{-- Pegawai --}}
      <div class="form-group mb-4">
        <label class="fw-semibold text-light">Pilih Pegawai Tujuan</label>
        <select name="pegawai_id" id="pegawai_id"
                class="form-control @error('pegawai_id') is-invalid @enderror">
          <option value="">-- Pilih Pegawai --</option>
        </select>
        @error('pegawai_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Keperluan --}}
      <div class="form-group mb-4">
        <label class="fw-semibold text-light">Keperluan</label>
        <textarea name="keperluan" id="keperluan" rows="3"
                  class="form-control @error('keperluan') is-invalid @enderror">{{ old('keperluan') }}</textarea>
        @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="text-end">
        <button type="submit" class="btn btn-save">
          <i class="fas fa-save me-2"></i> Simpan Kunjungan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Load pegawai berdasarkan bidang
  document.querySelectorAll('input[name="bidang_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
      let bidangId = this.value;
      let pegawaiSelect = document.getElementById('pegawai_id');
      pegawaiSelect.innerHTML = '<option value="">-- Memuat pegawai... --</option>';

      if (bidangId) {
        fetch(`/tamu/get-pegawai/${bidangId}`)
          .then(res => res.json())
          .then(data => {
            pegawaiSelect.innerHTML = '<option value="">-- Pilih Pegawai --</option>';
            data.forEach(p => {
              pegawaiSelect.innerHTML += `<option value="${p.id}">${p.user.name}</option>`;
            });
          })
          .catch(err => {
            pegawaiSelect.innerHTML = '<option value="">Gagal memuat pegawai</option>';
          });
      } else {
        pegawaiSelect.innerHTML = '<option value="">-- Pilih Pegawai --</option>';
      }
    });
  });
</script>
@endpush
