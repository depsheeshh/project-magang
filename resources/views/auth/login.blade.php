@extends('layouts.auth')

@section('title', 'Login')
@section('subtitle', 'Silakan masuk untuk melanjutkan')
@section('content')
<form method="POST" action="{{ route('login') }}" novalidate>
  @csrf

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

  <div class="d-flex justify-content-between mb-3">
    <a href="{{ route('password.request') }}" class="small text-info text-decoration-none">
      <i class="bi bi-question-circle me-1"></i>Lupa Password?
    </a>
  </div>

  <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-lg">
      <i class="bi bi-box-arrow-in-right me-2"></i> Login
    </button>
  </div>
</form>

<div class="auth-divider"><span>atau</span></div>

<div class="d-grid mb-3">
  <a href="{{ route('login.google') }}" class="btn btn-danger btn-lg">
    <i class="bi bi-google me-2"></i> Login dengan Google
  </a>
</div>
@endsection

@section('footer-links')
<div class="mt-3">
  <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">
    <i class="bi bi-house-door me-1"></i> Kembali ke Home
  </a>
</div>
<div class="mt-2">
  Belum punya akun? <a href="{{ route('register') }}" class="fw-bold">Daftar</a>
</div>
@endsection
