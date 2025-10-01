@extends('layouts.dashboard')

@section('title', 'Dashboard User')

@section('content')
<div class="container">
  <h1 class="mb-4"><i class="fas fa-user"></i> Dashboard User</h1>
  <p class="text-muted">Anda hanya bisa melihat data tamu yang relevan dengan Anda.</p>

  <div class="card dashboard-card">
    <div class="card-body">
      <h5 class="card-title">Data Tamu</h5>
      <p class="card-text text-muted">Daftar tamu akan ditampilkan di sini.</p>
      <a href="#" class="btn btn-outline-secondary btn-sm disabled">Segera Hadir</a>
    </div>
  </div>
</div>
@endsection
