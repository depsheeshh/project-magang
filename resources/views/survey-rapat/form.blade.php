@extends('layouts.guest')

@section('title', 'Isi Survey Rapat')

@section('content')
<div class="container mt-4">
  <h3 class="mb-3">Survey Rapat: {{ $survey->judul }}</h3>
  <p class="text-muted">Tipe: {{ ucfirst($survey->tipe) }}</p>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('warning'))
    <div class="alert alert-warning">{{ session('warning') }}</div>
  @endif

  <form action="{{ route('survey.rapat.submit', $survey->slug) }}" method="POST">
    @csrf
    <div class="form-group">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" required>
    </div>

    @if($survey->tipe === 'eksternal')
      <div class="form-group mt-2">
        <label>Instansi</label>
        <input type="text" name="instansi" class="form-control" required>
      </div>
    @endif

    {{-- Pertanyaan 1: Radio --}}
    <div class="form-group mt-3">
      <label><strong>Bagaimana kualitas rapat ini?</strong></label><br>
      <label><input type="radio" name="kualitas_rapat" value="Sangat Baik" required> Sangat Baik</label><br>
      <label><input type="radio" name="kualitas_rapat" value="Baik"> Baik</label><br>
      <label><input type="radio" name="kualitas_rapat" value="Cukup"> Cukup</label><br>
      <label><input type="radio" name="kualitas_rapat" value="Kurang"> Kurang</label>
    </div>

    {{-- Pertanyaan 2: Opini/Pendapat --}}
    <div class="form-group mt-3">
      <label><strong>Opini atau pendapat Anda tentang rapat ini:</strong></label>
      <textarea name="opini" class="form-control" rows="3" placeholder="Tuliskan pendapat Anda..."></textarea>
    </div>

    {{-- Pertanyaan 3: Textarea --}}
    <div class="form-group mt-3">
      <label><strong>Saran untuk rapat berikutnya:</strong></label>
      <textarea name="saran" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary mt-3">
      <i class="fas fa-paper-plane"></i> Kirim
    </button>
  </form>
</div>
@endsection
