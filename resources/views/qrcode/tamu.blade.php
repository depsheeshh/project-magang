@extends('layouts.app')

@section('title','QR Code Buku Tamu')
@section('content')
<div class="container text-center mt-5 py-5">
  <h2>Scan QR Code untuk Isi Buku Tamu</h2>

  {{-- Generate QR code menuju route tamu.form --}}
  {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(route('tamu.form')) !!}

  <p class="mt-3">
    Arahkan kamera HP Anda ke QR code ini untuk melanjutkan proses isi buku tamu.
  </p>
</div>
@endsection
