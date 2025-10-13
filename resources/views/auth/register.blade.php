@extends('layouts.app')

@section('title', 'Register')

@section('content')
<section class="page-section mt-5" id="register">
  <div class="container">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-4" data-aos="fade-down">
      Register
    </h2>
    <div class="divider-custom mb-4" data-aos="zoom-in">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-user-plus"></i></div>
      <div class="divider-custom-line"></div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-4" data-aos="fade-up">
          <div class="card-body p-4">
            <form method="POST" action="{{ route('register') }}" novalidate>
              @csrf

              {{-- Nama --}}
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('name') is-invalid @enderror"
                  id="name"
                  name="name"
                  type="text"
                  placeholder="Nama Lengkap"
                  value="{{ old('name') }}"
                  required
                  autocomplete="name"
                />
                <label for="name"><i class="fas fa-user me-2 text-primary"></i>Nama Lengkap</label>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Email --}}
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('email') is-invalid @enderror"
                  id="email"
                  name="email"
                  type="email"
                  placeholder="name@example.com"
                  value="{{ old('email') }}"
                  required
                  autocomplete="email"
                />
                <label for="email"><i class="fas fa-envelope me-2 text-primary"></i>Email</label>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Password --}}
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('password') is-invalid @enderror"
                  id="password"
                  name="password"
                  type="password"
                  placeholder="Password"
                  required
                  autocomplete="new-password"
                />
                <label for="password"><i class="fas fa-key me-2 text-primary"></i>Password</label>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              {{-- Konfirmasi Password --}}
              <div class="form-floating mb-3">
                <input
                  class="form-control @error('password_confirmation') is-invalid @enderror"
                  id="password_confirmation"
                  name="password_confirmation"
                  type="password"
                  placeholder="Konfirmasi Password"
                  required
                  autocomplete="new-password"
                />
                <label for="password_confirmation"><i class="fas fa-check-double me-2 text-primary"></i>Konfirmasi Password</label>
                @error('password_confirmation')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-grid">
                <button class="btn btn-primary btn-lg shadow-sm" type="submit">
                  <i class="fas fa-user-plus me-2"></i> Daftar
                </button>
              </div>
            </form>

            <div class="text-center mt-3">
              <small>Sudah punya akun?
                <a href="{{ route('login') }}" class="fw-bold text-primary">Login</a>
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
