@extends('layouts.auth')

@section('title', 'Lupa Password')
@section('subtitle', 'Masukkan email Anda untuk reset password')
@section('content')
<form method="POST" action="{{ route('password.email') }}" novalidate>
  @csrf
  <div class="form-floating mb-3">
    <input type="email" name="email" id="email"
           class="form-control @error('email') is-invalid @enderror"
           placeholder="name@example.com" required>
    <label for="email"><i class="bi bi-envelope me-2 text-primary"></i>Email</label>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-lg">
      <i class="bi bi-send me-2"></i> Kirim Link Reset
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
  Kembali ke <a href="{{ route('login') }}" class="fw-bold">Login</a>
</div>
@endsection
