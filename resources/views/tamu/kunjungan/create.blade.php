@extends('layouts.admin')

@section('title','Tambah Kunjungan')
@section('page-title','Form Tambah Kunjungan')

@push('style')
<style>
/* 🌙 Card Container */
.card-visit {
  border: none;
  border-radius: 18px;
  background: linear-gradient(145deg, #1b1b2f, #1e2743);
  color: #e0e8ff;
  box-shadow: 0 8px 20px rgba(0, 120, 255, 0.15);
  overflow: hidden;
  transition: all 0.3s ease;
}
.card-visit:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0, 150, 255, 0.25);
}

/* Header */
.card-visit .card-header {
  background: linear-gradient(90deg, #0077ff, #00b4ff);
  color: #fff;
  font-weight: 600;
  border: none;
  text-align: center;
  padding: 1.2rem;
  box-shadow: 0 3px 10px rgba(0, 132, 255, 0.3);
}

/* Custom Radio Card */
.custom-radio-card {
  position: relative;
  border: 1px solid rgba(0, 150, 255, 0.2);
  border-radius: 15px;
  padding: 15px;
  background: rgba(25, 35, 60, 0.85);
  color: #e8f1ff;
  transition: all 0.3s ease;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  width: 260px;
}
.custom-radio-card:hover {
  transform: translateY(-3px);
  border-color: #00bfff;
  box-shadow: 0 0 25px rgba(0,170,255,0.3);
}
.custom-radio-card input[type="radio"] {
  display: none;
}
.custom-radio-card input[type="radio"]:checked + .radio-content {
  border-left: 4px solid #00bfff;
  padding-left: 10px;
}
.custom-radio-card input[type="radio"]:checked + .radio-content .radio-icon {
  color: #00bfff;
  text-shadow: 0 0 8px rgba(0,180,255,0.5);
}
.radio-content {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}
.radio-icon {
  font-size: 26px;
  color: #8ab4f8;
  transition: color 0.3s ease;
}
.radio-title {
  font-weight: 600;
  font-size: 15px;
  color: #dce6ff;
}
.radio-desc {
  font-size: 13px;
  color: #a7b8d8;
  opacity: 0.85;
}

/* Tombol */
.btn-save {
  background: linear-gradient(135deg, #00aaff, #0077ff);
  border: none;
  border-radius: 12px;
  color: #fff;
  font-weight: 600;
  padding: 10px 25px;
  box-shadow: 0 0 15px rgba(0, 157, 255, 0.3);
  transition: all 0.3s ease;
}
.btn-save:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(0, 180, 255, 0.5);
}

/* Input Field Style */
.form-control {
  background: rgba(20, 30, 55, 0.85);
  border: 1px solid rgba(0, 150, 255, 0.2);
  color: #e0e8ff;
  border-radius: 10px;
}
.form-control:focus {
  border-color: #00bfff;
  box-shadow: 0 0 10px rgba(0,180,255,0.3);
  background: rgba(25, 35, 60, 0.95);
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
