@extends('layouts.app')

@section('content')
<div class="container mt-5 py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 mt-5">
      <div class="card shadow-sm">
        <div class="card-header">
          <h4 class="mb-0">Verifikasi Email</h4>
        </div>
        <div class="card-body">
          <p>Masukkan kode verifikasi yang sudah kami kirim ke email Anda.</p>

          @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
          @endif

          <form method="POST" action="{{ route('verification.verify') }}">
            @csrf
            <div class="form-group">
              <label for="code">Kode Verifikasi</label>
              <input type="text" name="code" class="form-control mb-3" required>
              @error('code') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-primary ">Verifikasi</button>
          </form>

          <hr>
          <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit" class="btn btn-link">Kirim Ulang Kode</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
