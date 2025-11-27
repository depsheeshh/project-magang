@extends('layouts.apelpagi')

@section('title','Apel Pagi - Telat')

@section('content')
<div class="card card-modern p-4 text-center">
  <i class="fa-solid fa-clock fa-3x text-warning mb-3"></i>
  <h4 class="text-warning">Anda Telat</h4>
  <p class="mt-2">
    {{ $pegawai->user->name }} (NIP: {{ $pegawai->nip }})<br>
    Telat
    <span class="badge
      @if($telatMenit <= 30) bg-warning
      @else bg-danger @endif">
      {{ $telatMenit }} menit
    </span>
    @if(isset($telatJamMenit))
      (≈ {{ $telatJamMenit }} jam)
    @endif
    dari jam 07:30.
  </p>
  <div class="alert alert-warning mt-3">
    Mohon lebih disiplin untuk apel pagi berikutnya.
  </div>
</div>
@endsection
