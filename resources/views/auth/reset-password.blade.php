@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<section class="page-section mt-5" id="reset-password">
  <div class="container">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Reset Password</h2>
    <div class="divider-custom">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
      <div class="divider-custom-line"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <form method="POST" action="{{ route('password.update') }}">
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
              autofocus
            />
            <label for="email">Email</label>
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
            />
            <label for="password">Password Baru</label>
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
            />
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            @error('password_confirmation')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-grid">
            <button class="btn btn-primary btn-lg" type="submit">Reset Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
