@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<section class="page-section mt-5" id="forgot-password">
  <div class="container">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-4" data-aos="fade-down">
      Lupa Password
    </h2>
    <div class="divider-custom mb-4" data-aos="zoom-in">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-unlock-alt"></i></div>
      <div class="divider-custom-line"></div>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-4" data-aos="fade-up">
          <div class="card-body p-4">
            @if (session('status'))
              <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
              </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" novalidate>
              @csrf
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

              <div class="d-grid">
                <button class="btn btn-primary btn-lg shadow-sm" type="submit">
                  <i class="fas fa-paper-plane me-2"></i> Kirim Link Reset
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
