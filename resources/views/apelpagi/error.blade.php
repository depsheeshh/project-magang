@extends('layouts.apelpagi')

@section('title','Apel Pagi - Error')

@section('content')
<div class="card card-modern p-4 text-center">
  <i class="fa-solid fa-triangle-exclamation fa-3x text-danger mb-3"></i>
  <h4 class="text-danger">Terjadi Kesalahan</h4>
  <p class="mt-2">{{ $message }}</p>
  <a href="#" onclick="history.back()" class="btn btn-secondary mt-3">
    <i class="fa fa-arrow-left me-2"></i> Kembali
  </a>
</div>
@endsection
