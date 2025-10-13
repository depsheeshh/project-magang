@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<section class="page-section mt-5" id="reset-password">
  <div class="container">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-4" data-aos="fade-down">
      Reset Password
    </h2>
    <div class="divider-custom mb-4" data-aos="zoom-in">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-sync-alt"></i></div>
      <div class="divider-custom-line"></div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-4" data-aos="fade-up">
          <div class="card-body p-4">
            <form method="POST" action="{{ route('password.update') }}" novalidate>
              @csrf
              <input type="hidden" name="token" value="{{ $request->route('token') }}">

              {{-- Email --}}
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('email') is-invalid @enderror"
                  id="email"
                  name="email"
                  type="email"
                  value="{{ old('email', $request->email) }}"
                  required
                  autocomplete="email"
                  autofocus
                />
                <label for="email"><i class="fas fa-envelope me-2 text-primary"></i>Email</label>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Password Baru --}}
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('password') is-invalid @enderror"
                  id="password"
                  name="password"
                  type="password"
                  placeholder="Password Baru"
                  required
                  autocomplete="new-password"
                />
                <label for="password"><i class="fas fa-key me-2 text-primary"></i>Password Baru</label>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Konfirmasi Password Baru --}}
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('password_confirmation') is-invalid @enderror"
                  id="password_confirmation"
                  name="password_confirmation"
                  type="password"
                  placeholder="Konfirmasi Password Baru"
                  required
                  autocomplete="new-password"
                />
                <label for="password_confirmation"><i class="fas fa-check-double me-2 text-primary"></i>Konfirmasi Password Baru</label>
                @error('password_confirmation')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-grid">
                <button class="btn btn-primary btn-lg shadow-sm" type="submit">
                  <i class="fas fa-sync-alt me-2"></i> Reset Password
                </button>
              </div>
            </form>

            <div class="text-center mt-3">
              <small>Kembali ke <a href="{{ route('login') }}" class="fw-bold text-primary">Login</a></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
