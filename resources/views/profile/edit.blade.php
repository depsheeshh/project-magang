@extends('layouts.admin')

@section('title','Profil Saya')
@section('page-title','Profil Saya')

@section('content')
<style>
/* 🌌 Tema Gelap Modern */
.card-profile {
  border: none;
  border-radius: 20px;
  background: linear-gradient(145deg, #1b1b2f, #1f1f3b);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
  color: #e0e0e0;
  overflow: hidden;
  transition: all 0.3s ease-in-out;
}

.card-profile:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0, 150, 255, 0.25);
}

/* Header Profil */
.card-header-profile {
  background: linear-gradient(90deg, #0066ff, #00b4ff);
  color: #fff;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 12px;
  border: none;
  box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
}

.card-header-profile i {
  font-size: 2.2rem;
}

.card-header-profile h5 {
  font-weight: 600;
  margin: 0;
  letter-spacing: 0.5px;
}

/* Input Styling */
.form-floating {
  position: relative;
}

.form-control,
textarea.form-control {
  border-radius: 12px;
  border: 1px solid rgba(0, 170, 255, 0.25);
  background: rgba(30, 40, 60, 0.8);
  color: #e8f1ff;
  transition: all 0.3s ease;
}

.form-control:focus,
textarea.form-control:focus {
  border-color: #00bfff;
  background: rgba(40, 55, 80, 0.95);
  box-shadow: 0 0 10px rgba(0, 170, 255, 0.5);
  color: #fff;
}

label {
  color: #bcd0f5;
}

/* Section Heading */
h5.text-section {
  border-left: 4px solid #00bfff;
  padding-left: 10px;
  margin-top: 30px;
  margin-bottom: 20px;
  font-weight: 600;
  color: #a7c7ff;
}

/* Tombol Simpan */
.btn-save {
  background: linear-gradient(135deg, #00aaff, #0066ff);
  border: none;
  color: #fff;
  font-weight: 600;
  border-radius: 12px;
  padding: 12px 28px;
  box-shadow: 0 0 20px rgba(0, 157, 255, 0.3);
  transition: all 0.3s ease;
}

.btn-save:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(0, 200, 255, 0.5);
}

/* Animasi Fade */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}
.card-body {
  animation: fadeInUp 0.6s ease forwards;
}

/* Responsive */
@media (max-width: 768px) {
  .card-body {
    padding: 1.5rem;
  }
}
</style>

<div class="card card-profile shadow-lg">
  <div class="card-header-profile">
    <i class="fas fa-user-circle"></i>
    <h5>Profil Saya</h5>
  </div>

  <div class="card-body p-4">
    <form action="{{ route('profile.update') }}" method="POST" novalidate>
      @csrf @method('PUT')

      {{-- Data Umum --}}
      <h5 class="text-section"><i class="fas fa-id-card me-2"></i> Data Umum</h5>
      <div class="form-floating mb-3">
        <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control" id="name" required>
        <label for="name">Nama Lengkap</label>
      </div>

      <div class="form-floating mb-4">
        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control" id="email" required>
        <label for="email">Email</label>
      </div>

      {{-- Role Tamu --}}
      @if($user->hasRole('tamu'))
        <h5 class="text-section"><i class="fas fa-user-friends me-2"></i> Data Tamu</h5>
        <div class="form-floating mb-3">
          <input type="text" name="instansi" value="{{ old('instansi',$user->tamu->instansi ?? '') }}" class="form-control" id="instansi">
          <label for="instansi">Instansi Tamu</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" name="no_hp" value="{{ old('no_hp',$user->tamu->no_hp ?? '') }}" class="form-control" id="no_hp">
          <label for="no_hp">No HP</label>
        </div>
        <div class="form-floating mb-4">
          <textarea name="alamat" class="form-control" id="alamat" style="height: 100px">{{ old('alamat',$user->tamu->alamat ?? '') }}</textarea>
          <label for="alamat">Alamat</label>
        </div>
      @endif

      {{-- Role Pegawai --}}
      @if($user->hasRole('pegawai'))
        <h5 class="text-section"><i class="fas fa-briefcase me-2"></i> Data Pegawai</h5>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" value="{{ $user->pegawai?->bidang?->nama_bidang ?? '-' }}" id="bidang" disabled>
          <label for="bidang">Bidang</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" value="{{ $user->pegawai?->jabatan?->nama_jabatan ?? '-' }}" id="jabatan" disabled>
          <label for="jabatan">Jabatan</label>
        </div>
        <div class="form-floating mb-4">
          <input type="text" name="telepon" value="{{ old('telepon',$user->pegawai->telepon ?? '') }}" class="form-control" id="telepon">
          <label for="telepon">No HP</label>
        </div>
      @endif

      {{-- Role Admin --}}
      @if($user->hasRole('admin'))
        <div class="alert alert-info border-0 rounded-3 shadow-sm">
          <i class="fas fa-info-circle me-2"></i>
          Sebagai admin, Anda hanya bisa mengubah nama & email di sini.
        </div>
      @endif

      {{-- Tombol Simpan --}}
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-save">
          <i class="fas fa-save me-2"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
