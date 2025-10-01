@extends('layouts.admin')

@section('title', 'Ubah Password')
@section('page-title', 'Ubah Password')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h4>Form Ubah Password</h4>
      </div>
      <div class="card-body">

        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
          </div>
        @endif --}}

        <form method="POST" action="{{ route('password.change.update') }}" class="needs-validation" novalidate>
          @csrf

          <div class="form-group">
            <label for="current_password">Password Lama</label>
            <input type="password"
                   class="form-control @error('current_password') is-invalid @enderror"
                   id="current_password"
                   name="current_password"
                   required>
            @error('current_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="new_password">Password Baru</label>
            <input type="password"
                   class="form-control @error('new_password') is-invalid @enderror"
                   id="new_password"
                   name="new_password"
                   required>
            @error('new_password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="form-group">
            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
            <input type="password"
                   class="form-control"
                   id="new_password_confirmation"
                   name="new_password_confirmation"
                   required>
          </div>

          <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">Update Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
