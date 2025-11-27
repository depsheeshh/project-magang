@extends('layouts.apelpagi')

@section('title','Apel Pagi - Tepat Waktu')

@section('content')
<div class="card card-modern p-4 text-center">
  <i class="fa-solid fa-check-circle fa-3x text-success mb-3"></i>
  <h4 class="text-success">Selamat!</h4>
  <p class="mt-2">
    {{ $pegawai->user->name }} (NIP: {{ $pegawai->nip }})<br>
    Anda masuk tepat waktu pada {{ $now->format('H:i') }}.
  </p>
  <div class="alert alert-success mt-3">
    Kehadiran Anda tercatat dengan baik. Terima kasih atas kedisiplinan Anda.
  </div>
</div>
@endsection
