@extends('layouts.admin')

@section('title','Profil Saya')
@section('page-title','Profil Saya')

@section('content')
<div class="card">
  <div class="card-header"><h4>Profil Saya</h4></div>
  <div class="card-body">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
      @csrf @method('PUT')

      <div class="form-group">
        <label>Nama</label>
        <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control" required>
      </div>

      {{-- Role Tamu --}}
      @if($user->hasRole('tamu'))
        <div class="form-group">
          <label>Instansi</label>
          <input type="text" name="instansi" value="{{ old('instansi',$user->tamu->instansi ?? '') }}" class="form-control">
        </div>
        <div class="form-group">
          <label>No HP</label>
          <input type="text" name="no_hp" value="{{ old('no_hp',$user->tamu->no_hp ?? '') }}" class="form-control">
        </div>
        <div class="form-group">
          <label>Alamat</label>
          <textarea name="alamat" class="form-control">{{ old('alamat',$user->tamu->alamat ?? '') }}</textarea>
        </div>
      @endif

      {{-- Role Pegawai --}}
      @if($user->hasRole('pegawai'))
        <div class="form-group">
          <label>Bidang</label>
          <input type="text" class="form-control" value="{{ $user->pegawai?->bidang?->nama_bidang ?? '-' }}" disabled>
        </div>
        <div class="form-group">
          <label>Jabatan</label>
          <input type="text" class="form-control" value="{{ $user->pegawai?->jabatan?->nama_jabatan ?? '-' }}" disabled>
        </div>
        <div class="form-group">
          <label>No HP</label>
          <input type="text" name="telepon" value="{{ old('telepon',$user->pegawai->telepon ?? '') }}" class="form-control">
        </div>
      @endif

      {{-- Role Admin --}}
      @if($user->hasRole('admin'))
        <div class="alert alert-info">
          Sebagai admin, Anda hanya bisa mengubah nama & email di sini.
        </div>
      @endif

      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
  </div>
</div>
@endsection
