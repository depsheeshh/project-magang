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
            <label class="form-label">Bagaimana pengalaman Anda?</label>
            <select name="rating" class="form-select" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" {{ $survey->rating == $i ? 'selected' : '' }}>
                  {{ $i }}
                </option>
              @endfor
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Saran / Masukan</label>
            <textarea name="feedback" class="form-control" rows="3">{{ $survey->feedback }}</textarea>
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
