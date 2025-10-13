@extends('layouts.app')

@section('title', 'Login')

@section('content')
<section class="page-section mt-5" id="login">
  <div class="container">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-4" data-aos="fade-down">
      Login
    </h2>
    <div class="divider-custom mb-4" data-aos="zoom-in">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-lock"></i></div>
      <div class="divider-custom-line"></div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-4" data-aos="fade-up">
          <div class="card-body p-4">
            {{-- Form login manual --}}
            <form method="POST" action="{{ route('login') }}" novalidate>
              @csrf

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
                  autocomplete="current-password"
                />
                <label for="password"><i class="fas fa-key me-2 text-primary"></i>Password</label>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('password.request') }}" class="small text-decoration-none">
                  <i class="fas fa-question-circle me-1"></i>Lupa Password?
                </a>
              </div>

              <div class="d-grid">
                <button class="btn btn-primary btn-lg shadow-sm" type="submit">
                  <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
              </div>
            </form>

            {{-- Divider --}}
            <div class="text-center my-3">
              <span class="text-muted">atau</span>
            </div>

            {{-- Tombol Social Login --}}
            <div class="d-grid gap-2">
              <a href="{{ route('login.google') }}" class="btn btn-danger btn-lg shadow-sm">
                <i class="fab fa-google me-2"></i> Login dengan Google
            </a>
            </div>

            <div class="text-center mt-3">
              <small>Belum punya akun?
                <a href="{{ route('register') }}" class="fw-bold text-primary">Daftar</a>
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
