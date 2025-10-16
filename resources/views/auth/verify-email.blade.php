@extends('layouts.auth')

@section('title', 'Verifikasi Email')
@section('subtitle', 'Masukkan kode verifikasi yang dikirim ke email Anda')

@section('content')
<form method="POST" action="{{ route('verification.verify') }}">
  @csrf
  <div class="form-floating mb-3">
    <input type="text" name="code" id="code"
           class="form-control @error('code') is-invalid @enderror"
           placeholder="Kode Verifikasi" required>
    <label for="code"><i class="bi bi-shield-lock me-2 text-primary"></i>Kode Verifikasi</label>
    @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-lg">
      <i class="bi bi-check-circle me-2"></i> Verifikasi
    </button>
  </div>
</form>

<div class="text-center mt-3">
  <form method="POST" action="{{ route('verification.resend') }}">
    @csrf
    <button type="submit" class="btn btn-outline-info btn-sm">
      <i class="bi bi-arrow-repeat me-1"></i> Kirim Ulang Kode
    </button>
  </form>
</div>
@endsection

