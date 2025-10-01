@extends('layouts.dashboard') {{-- atau layouts.app jika ingin di halaman publik yang terproteksi --}}

@section('title', 'Ubah Password')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <h2 class="mb-4">Ubah Password</h2>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
      @csrf

      <div class="form-floating mb-3">
        <input type="password"
               class="form-control @error('current_password') is-invalid @enderror"
               id="current_password"
               name="current_password"
               placeholder="Password Lama"
               required>
        <label for="current_password">Password Lama</label>
        @error('current_password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-floating mb-3">
        <input type="password"
               class="form-control @error('new_password') is-invalid @enderror"
               id="new_password"
               name="new_password"
               placeholder="Password Baru"
               required>
        <label for="new_password">Password Baru</label>
        @error('new_password')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-floating mb-3">
        <input type="password"
               class="form-control"
               id="new_password_confirmation"
               name="new_password_confirmation"
               placeholder="Konfirmasi Password Baru"
               required>
        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">Update Password</button>
      </div>
    </form>
  </div>
</div>
@endsection
