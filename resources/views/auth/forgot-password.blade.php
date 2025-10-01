@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<section class="page-section mt-5" id="forgot-password">
  <div class="container">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Lupa Password</h2>
    <div class="divider-custom">
      <div class="divider-custom-line"></div>
      <div class="divider-custom-icon"><i class="fas fa-star"></i></div>
      <div class="divider-custom-line"></div>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-6">
        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
          @csrf
          <div class="form-floating mb-3">
            <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" required />
            <label for="email">Email</label>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary btn-lg" type="submit">Kirim Link Reset</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection
