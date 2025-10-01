@extends('layouts.admin')

@section('title', 'Tambah User')
@section('page-title', 'Tambah User')

@section('content')
<div class="row">
  <div class="col-12 col-md-8 col-lg-8">
    <div class="card">
      <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <div class="card-header">
          <h4>Form Tambah User</h4>
        </div>
        <div class="card-body">
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control @error('role') is-invalid @enderror" required>
              <option value="">-- Pilih Role --</option>
              @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                  {{ ucfirst($role->name) }}
                </option>
              @endforeach
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
        <div class="card-footer text-right">
          <button class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
