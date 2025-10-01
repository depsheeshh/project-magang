@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="container">
  <h1 class="mb-4">Halo, {{ Auth::user()->name }} 👋</h1>

  @can('view dashboard')
    <div class="alert alert-info">
      <i class="fas fa-info-circle"></i> Anda bisa mengakses dashboard umum.
    </div>
  @endcan

  <div class="row">
    @role('admin')
      <div class="col-md-6 mb-3">
        <div class="card dashboard-card">
          <div class="card-body text-center">
            <i class="fas fa-user-shield fa-3x text-primary mb-3"></i>
            <h5 class="card-title">Dashboard Admin</h5>
            <p class="card-text text-muted">Kelola data tamu, frontliner, dan pegawai.</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Masuk</a>
          </div>
        </div>
      </div>
    @endrole

    @role('user')
      <div class="col-md-6 mb-3">
        <div class="card dashboard-card">
          <div class="card-body text-center">
            <i class="fas fa-user fa-3x text-secondary mb-3"></i>
            <h5 class="card-title">Dashboard User</h5>
            <p class="card-text text-muted">Lihat data tamu yang relevan dengan Anda.</p>
            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary">Masuk</a>
          </div>
        </div>
      </div>
    @endrole
  </div>
</div>
@endsection
