@extends('layouts.guest')

@section('title','Survey Kepuasan')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-8">
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Survey Kepuasan</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('survey.submit', [$kunjungan->id, Str::afterLast($survey->link,'/')] ) }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">Kemudahan Proses Registrasi</label>
            <select name="kemudahan_registrasi" class="form-select" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" {{ $survey->kemudahan_registrasi == $i ? 'selected' : '' }}>
                  {{ $i }}
                </option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Keramahan Pelayanan</label>
            <select name="keramahan_pelayanan" class="form-select" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" {{ $survey->keramahan_pelayanan == $i ? 'selected' : '' }}>
                  {{ $i }}
                </option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Waktu Tunggu</label>
            <select name="waktu_tunggu" class="form-select" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" {{ $survey->waktu_tunggu == $i ? 'selected' : '' }}>
                  {{ $i }}
                </option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Rating Umum</label>
            <select name="rating" class="form-select" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" {{ $survey->rating == $i ? 'selected' : '' }}>
                  {{ $i }} - {{ ['Sangat Buruk','Buruk','Cukup','Baik','Sangat Baik'][$i-1] }}
                </option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Saran / Masukan</label>
            <textarea name="saran" class="form-control" rows="3">{{ $survey->saran }}</textarea>
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Kirim
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
