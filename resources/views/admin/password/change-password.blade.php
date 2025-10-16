@extends('layouts.admin')

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')

@section('content')
<style>
/* 🌙 Dark Glassy Card */
.card-password {
  border: none;
  border-radius: 18px;
  background: linear-gradient(145deg, #1b1b2f, #1e2743);
  box-shadow: 0 8px 20px rgba(0, 120, 255, 0.15);
  overflow: hidden;
  transition: all 0.3s ease;
}

.card-password:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(0, 170, 255, 0.25);
}

.card-password .card-header {
  background: linear-gradient(90deg, #0066ff, #00b4ff);
  color: #fff;
  font-weight: 600;
  letter-spacing: 0.5px;
  border: none;
  text-align: center;
  padding: 1.2rem;
}

/* Input */
.form-control {
  border-radius: 10px;
  border: 1px solid rgba(0, 150, 255, 0.2);
  background: rgba(25, 35, 60, 0.85);
  color: #e0e8ff;
  transition: all 0.3s ease;
}
.form-control:focus {
  border-color: #00bfff;
  box-shadow: 0 0 10px rgba(0, 180, 255, 0.4);
  background: rgba(35, 45, 75, 0.95);
}

/* Tombol */
.btn-gradient {
  background: linear-gradient(135deg, #00aaff, #0077ff);
  border: none;
  border-radius: 10px;
  padding: 10px 25px;
  color: #fff;
  font-weight: 600;
  box-shadow: 0 0 15px rgba(0, 157, 255, 0.3);
  transition: all 0.3s ease;
}
.btn-gradient:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px rgba(0, 180, 255, 0.5);
}

/* Animasi Fade */
@keyframes fadeInUp {
  from {opacity: 0; transform: translateY(15px);}
  to {opacity: 1; transform: translateY(0);}
}
.card-body {
  animation: fadeInUp 0.5s ease forwards;
}
</style>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card card-password">
      <div class="card-header">
        <i class="fas fa-lock me-2"></i> Ubah Password
      </div>

      <div class="card-body p-4">
        @if (session('status'))
          <div class="alert alert-success border-0 rounded-3 shadow-sm">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
          </div>
        @endif

        <form method="POST" action="{{ route('password.change.update') }}" novalidate>
          @csrf

          <div class="form-group mb-3">
            <label for="current_password" class="fw-semibold">Password Lama</label>
            <input type="password" name="current_password" id="current_password"
              class="form-control @error('current_password') is-invalid @enderror" required>
            @error('current_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-3">
            <label for="new_password" class="fw-semibold">Password Baru</label>
            <input type="password" name="new_password" id="new_password"
              class="form-control @error('new_password') is-invalid @enderror" required>
            @error('new_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group mb-4">
            <label for="new_password_confirmation" class="fw-semibold">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-gradient">
              <i class="fas fa-sync-alt me-2"></i> Perbarui Password
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
