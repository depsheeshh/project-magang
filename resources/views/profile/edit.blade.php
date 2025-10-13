@extends('layouts.admin')

@section('title','Profil Saya')
@section('page-title','Profil Saya')

@section('content')
<div class="card shadow-lg border-0 rounded-4">
  <div class="card-header bg-primary text-white d-flex align-items-center">
    <i class="fas fa-user-circle fa-2x me-2"></i>
  </div>

  <div class="card-body p-4">
    {{-- @if(session('success'))
      <div class="alert alert-success" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      </div>
    @endif --}}

    <form action="{{ route('profile.update') }}" method="POST" novalidate>
      @csrf @method('PUT')

      {{-- Data Umum --}}
      <h5 class="text-secondary mb-3"><i class="fas fa-id-card me-2"></i> Data Umum</h5>
      <div class="form-floating mb-3">
        <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control" id="name" required>
        <label for="name">Nama Lengkap</label>
      </div>

      <div class="form-floating mb-4">
        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control" id="email" required>
        <label for="email">Email</label>
      </div>

      {{-- Role Tamu --}}
      @if($user->hasRole('tamu'))
        <h5 class="text-secondary mb-3"><i class="fas fa-user-friends me-2"></i> Data Tamu</h5>
        <div class="form-floating mb-3">
          <input type="text" name="instansi" value="{{ old('instansi',$user->tamu->instansi ?? '') }}" class="form-control" id="instansi">
          <label for="instansi">Instansi</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" name="no_hp" value="{{ old('no_hp',$user->tamu->no_hp ?? '') }}" class="form-control" id="no_hp">
          <label for="no_hp">No HP</label>
        </div>
        <div class="form-floating mb-4">
          <textarea name="alamat" class="form-control" id="alamat" style="height: 100px">{{ old('alamat',$user->tamu->alamat ?? '') }}</textarea>
          <label for="alamat">Alamat</label>
        </div>
      @endif

      {{-- Role Pegawai --}}
      @if($user->hasRole('pegawai'))
        <h5 class="text-secondary mb-3"><i class="fas fa-briefcase me-2"></i> Data Pegawai</h5>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" value="{{ $user->pegawai?->bidang?->nama_bidang ?? '-' }}" id="bidang" disabled>
          <label for="bidang">Bidang</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" value="{{ $user->pegawai?->jabatan?->nama_jabatan ?? '-' }}" id="jabatan" disabled>
          <label for="jabatan">Jabatan</label>
        </div>
        <div class="form-floating mb-4">
          <input type="text" name="telepon" value="{{ old('telepon',$user->pegawai->telepon ?? '') }}" class="form-control" id="telepon">
          <label for="telepon">No HP</label>
        </div>
      @endif

      {{-- Role Admin --}}
      @if($user->hasRole('admin'))
        <div class="alert alert-info">
          <i class="fas fa-info-circle me-2"></i>
          Sebagai admin, Anda hanya bisa mengubah nama & email di sini.
        </div>
      @endif

      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
          <i class="fas fa-save me-2"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
