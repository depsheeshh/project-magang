@extends('layouts.auth')

@section('title', 'Reset Password')
@section('subtitle', 'Masukkan password baru Anda')
@section('content')
<form method="POST" action="{{ route('password.update') }}" novalidate>
  @csrf
  <input type="hidden" name="token" value="{{ $request->route('token') }}">

  <div class="form-floating mb-3">
    <input type="email" name="email" id="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $request->email) }}" required>
    <label for="email"><i class="bi bi-envelope me-2 text-primary"></i>Email</label>
    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="form-floating mb-3 position-relative">
    <input type="password" name="password" id="password"
           class="form-control @error('password') is-invalid @enderror"
           placeholder="Password Baru" required>
    <label for="password"><i class="bi bi-key me-2 text-primary"></i>Password Baru</label>
    <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3"
          onclick="togglePassword('password', this)">
      <i class="bi bi-eye"></i>
    </span>
    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="form-floating mb-3 position-relative">
    <input type="password" name="password_confirmation" id="password_confirmation"
           class="form-control @error('password_confirmation') is-invalid @enderror"
           placeholder="Konfirmasi Password Baru" required>
    <label for="password_confirmation"><i class="bi bi-check2-all me-2 text-primary"></i>Konfirmasi Password Baru</label>
    <span class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3"
          onclick="togglePassword('password_confirmation', this)">
      <i class="bi bi-eye"></i>
    </span>
    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-lg">
      <i class="bi bi-arrow-repeat me-2"></i> Reset Password
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

@push('scripts')
<script>
  function togglePassword(fieldId, el) {
    const input = document.getElementById(fieldId);
    const icon = el.querySelector('i');
    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("bi-eye");
      icon.classList.add("bi-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("bi-eye-slash");
      icon.classList.add("bi-eye");
    }
  }
</script>
@endpush
