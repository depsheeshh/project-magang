@extends('layouts.admin')

@section('title','Detail Pegawai')
@section('page-title','Detail Pegawai')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Detail Pegawai</h4>
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Nama</dt>
      <dd class="col-sm-9">{{ $pegawai->user->name }}</dd>

      <dt class="col-sm-3">Email</dt>
      <dd class="col-sm-9">{{ $pegawai->user->email }}</dd>

      <dt class="col-sm-3">NIP</dt>
      <dd class="col-sm-9">{{ $pegawai->nip ?? '-' }}</dd>

      <dt class="col-sm-3">Telepon</dt>
      <dd class="col-sm-9">{{ $pegawai->telepon ?? '-' }}</dd>

      <dt class="col-sm-3">Bidang</dt>
      <dd class="col-sm-9">{{ $pegawai->bidang->nama_bidang ?? '-' }}</dd>

      <dt class="col-sm-3">Jabatan</dt>
      <dd class="col-sm-9">{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</dd>

      <dt class="col-sm-3">Dibuat</dt>
      <dd class="col-sm-9">{{ $pegawai->created_at->format('d-m-Y H:i:s') }}</dd>

      <dt class="col-sm-3">Terakhir Diperbarui</dt>
      <dd class="col-sm-9">{{ $pegawai->updated_at->format('d-m-Y H:i:s') }}</dd>
    </dl>
    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>
@endsection
