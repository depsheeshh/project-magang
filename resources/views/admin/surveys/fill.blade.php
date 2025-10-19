@extends('layouts.admin')

@section('title','Isi Survey')
@section('page-title','Isi Survey Tamu')

@section('content')
<div class="card">
  <div class="card-header"><h4>Isi Survey Tamu</h4></div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.surveys.fill.submit',$survey->id) }}">
      @csrf
      <div class="mb-3">
        <label>Kemudahan Registrasi</label>
        <select name="kemudahan_registrasi" class="form-control" required>
          @for($i=1;$i<=5;$i++)
            <option value="{{ $i }}">{{ $i }}</option>
          @endfor
        </select>
      </div>
      <div class="mb-3">
        <label>Keramahan Pelayanan</label>
        <select name="keramahan_pelayanan" class="form-control" required>
          @for($i=1;$i<=5;$i++)
            <option value="{{ $i }}">{{ $i }}</option>
          @endfor
        </select>
      </div>
      <div class="mb-3">
        <label>Waktu Tunggu</label>
        <select name="waktu_tunggu" class="form-control" required>
          @for($i=1;$i<=5;$i++)
            <option value="{{ $i }}">{{ $i }}</option>
          @endfor
        </select>
      </div>
      <div class="mb-3">
        <label>Rating Umum</label>
        <select name="rating" class="form-control" required>
          <option value="">-- Pilih --</option>
          <option value="1">1 - Sangat Buruk</option>
          <option value="2">2 - Buruk</option>
          <option value="3">3 - Cukup</option>
          <option value="4">4 - Baik</option>
          <option value="5">5 - Sangat Baik</option>
        </select>
      </div>
      <div class="mb-3">
        <label>Saran / Masukan</label>
        <textarea name="saran" class="form-control" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ route('admin.surveys.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
