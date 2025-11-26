@extends('layouts.admin')

@section('title','Menu Terkunci')
@section('page-title','Menu Terkunci')

@section('content')
<div class="card shadow-sm text-center p-4">
  <h4 class="mb-3 text-danger"><i class="fas fa-lock"></i> Menu Terkunci</h4>
  <p class="text-muted">
    Akun Anda sudah memiliki role <strong>Pegawai</strong>, namun belum terhubung dengan data pegawai resmi.
  </p>
  <p class="text-muted">
    Silakan hubungi admin DKIS untuk melengkapi data pegawai agar menu <strong>Riwayat Kunjungan</strong> dan <strong>Notifikasi Tamu</strong> bisa diakses.
  </p>
  <a href="{{ route('dashboard.index') }}" class="btn btn-primary mt-3">
    <i class="fas fa-home"></i> Kembali ke Dashboard
  </a>
</div>
@endsection
