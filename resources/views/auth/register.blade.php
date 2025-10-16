@extends('layouts.auth')

@section('title', 'Register')
@section('subtitle', 'Buat akun baru untuk mulai menggunakan sistem')
@section('content')
<form method="POST" action="{{ route('register') }}" novalidate>
  @csrf

  <div class="form-floating mb-3">
    <input type="text" name="name" id="name"
           class="form-control @error('name') is-invalid @enderror"
           placeholder="Nama Lengkap" required>
    <label for="name"><i class="bi bi-person me-2 text-primary"></i>Nama Lengkap</label>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="form-floating mb-3">
    <input type="email" name="email" id="email"
           class="form-control @error('email') is-invalid @enderror"
           placeholder="name@example.com" required>
    <label for="email"><i class="bi bi-envelope me-2 text-primary"></i>Email</label>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="form-floating mb-3">
    <input type="password" name="password" id="password"
           class="form-control @error('password') is-invalid @enderror"
           placeholder="Password" required>
    <label for="password"><i class="bi bi-key me-2 text-primary"></i>Password</label>
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="form-floating mb-3">
    <input type="password" name="password_confirmation" id="password_confirmation"
           class="form-control @error('password_confirmation') is-invalid @enderror"
           placeholder="Konfirmasi Password" required>
    <label for="password_confirmation"><i class="bi bi-check2-all me-2 text-primary"></i>Konfirmasi Password</label>
    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-lg">
      <i class="bi bi-person-plus me-2"></i> Daftar
    </button>
  </div>
</form>
@endsection

@section('footer-links')
<div class="mt-3">
  <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">
    <i class="bi bi-house-door me-1"></i> Kembali ke Home
  </a>
</div>
<div class="mt-2">
  Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold">Login</a>
</div>
@endsection
