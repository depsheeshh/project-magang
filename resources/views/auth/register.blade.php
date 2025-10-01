@extends('layouts.app')

@section('title', 'Register')

@section('content')
<section class="page-section mt-5" id="register">
  <div class="container">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Register</h2>
    <div class="divider-custom">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
      <div class="divider-custom-line"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <form method="POST" action="{{ route('register') }}">
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
            />
            <label for="name">Nama</label>
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
            />
            <label for="email">Email</label>
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
            />
            <label for="password">Password</label>
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
            />
            <label for="password_confirmation">Konfirmasi Password</label>
            @error('password_confirmation')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-grid">
            <button class="btn btn-primary btn-lg" type="submit">Daftar</button>
          </div>
        </form>

        <div class="text-center mt-3">
          <small>Sudah punya akun? <a href="{{ route('login') }}">Login</a></small>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
