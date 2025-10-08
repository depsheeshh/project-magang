@extends('layouts.admin')

@section('title','Detail Bidang')
@section('page-title','Detail Bidang')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>{{ $bidang->nama_bidang }}</h4>
  </div>
  <div class="card-body">
    <p style="line-height: 1.6; text-align: justify;">
      {{ $bidang->deskripsi }}
    </p>
    <a href="{{ route('admin.bidang.index') }}" class="btn btn-secondary mt-3">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>
</div>
@endsection
