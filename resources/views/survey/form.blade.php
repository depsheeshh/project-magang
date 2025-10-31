@extends('layouts.guest')

@section('title','Survey Kepuasan')

@push('styles')
<style>
  /* ✨ Animasi */
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .survey-card {
    animation: fadeInUp 0.6s ease-in-out;
    border: none;
    border-radius: 16px;
    box-shadow: 0 6px 24px rgba(0,0,0,0.08);
    overflow: hidden;
    background-color: #fff;
    transition: background 0.4s, color 0.4s;
  }

  .survey-header {
    background: linear-gradient(135deg, #2563eb, #1e3a8a);
    color: #fff;
    padding: 25px;
    text-align: center;
  }

  .survey-header h5 {
    font-weight: 700;
    font-size: 1.2rem;
    margin: 0;
  }

  .survey-body {
    padding: 30px;
  }

  .form-label {
    font-weight: 600;
    color: #1e293b;
  }

  select.form-select,
  textarea.form-control {
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    transition: all 0.25s ease;
  }
  select.form-select:focus,
  textarea.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
  }

  /* 🌟 Rating Label */
  .rating-group label {
    cursor: pointer;
    transition: transform 0.2s;
  }
  .rating-group label:hover {
    transform: scale(1.05);
  }

  /* ✨ Submit Button */
  .btn-submit {
    background: linear-gradient(135deg, #16a34a, #15803d);
    border: none;
    border-radius: 12px;
    padding: 12px 0;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  .btn-submit:hover {
    background: linear-gradient(135deg, #15803d, #166534);
    transform: translateY(-2px);
  }

  .intro-text {
    color: #475569;
    font-size: 14px;
    margin-bottom: 25px;
  }

  /* 🌙 Dark Mode */
  @media (prefers-color-scheme: dark) {
    body {
      background-color: #0f172a !important;
      color: #e2e8f0;
    }

    .survey-card {
      background-color: #1e293b;
      box-shadow: 0 0 15px rgba(0,0,0,0.6);
    }

    .survey-header {
      background: linear-gradient(135deg, #1e40af, #1e3a8a);
    }

    .form-label { color: #f1f5f9; }
    .intro-text { color: #cbd5e1; }

    select.form-select,
    textarea.form-control {
      background-color: #334155;
      border-color: #475569;
      color: #e2e8f0;
    }

    select.form-select:focus,
    textarea.form-control:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 3px rgba(37,99,235,0.25);
    }

    .btn-submit {
      background: linear-gradient(135deg, #22c55e, #15803d);
    }
    .btn-submit:hover {
      background: linear-gradient(135deg, #15803d, #166534);
    }
  }
</style>
@endpush

@section('content')
<div class="col-md-8 mx-auto">
  <div class="survey-card">
    <div class="survey-header">
      <h5><i class="fas fa-face-smile me-2"></i> Survey Kepuasan Pengunjung</h5>
    </div>

    <div class="survey-body">
      <p class="intro-text">
        Mohon luangkan waktu sejenak untuk mengisi survey ini.
        Masukan Anda sangat berharga untuk peningkatan kualitas pelayanan kami 💙
      </p>

      <form method="POST" action="{{ route('survey.submit', [$kunjungan->id, Str::afterLast($survey->link,'/')] ) }}">
        @csrf

        <div class="mb-3">
          <label class="form-label"><i class="fas fa-id-card"></i> Kemudahan Proses Registrasi</label>
          <select name="kemudahan_registrasi" class="form-select" required>
            <option value="">-- Pilih Nilai --</option>
            @for($i=1;$i<=5;$i++)
              <option value="{{ $i }}" {{ $survey->kemudahan_registrasi == $i ? 'selected' : '' }}>
                {{ $i }}
              </option>
            @endfor
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="fas fa-handshake"></i> Keramahan Pelayanan</label>
          <select name="keramahan_pelayanan" class="form-select" required>
            <option value="">-- Pilih Nilai --</option>
            @for($i=1;$i<=5;$i++)
              <option value="{{ $i }}" {{ $survey->keramahan_pelayanan == $i ? 'selected' : '' }}>
                {{ $i }}
              </option>
            @endfor
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="fas fa-clock"></i> Waktu Tunggu</label>
          <select name="waktu_tunggu" class="form-select" required>
            <option value="">-- Pilih Nilai --</option>
            @for($i=1;$i<=5;$i++)
              <option value="{{ $i }}" {{ $survey->waktu_tunggu == $i ? 'selected' : '' }}>
                {{ $i }}
              </option>
            @endfor
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="fas fa-star"></i> Rating Umum</label>
          <div class="d-flex flex-wrap gap-3 rating-group">
            @for($i=1;$i<=5;$i++)
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" {{ $survey->rating == $i ? 'checked' : '' }} required>
                <label class="form-check-label" for="rating{{ $i }}">
                  <i class="fas fa-star text-warning"></i> {{ ['Sangat Buruk','Buruk','Cukup','Baik','Sangat Baik'][$i-1] }}
                </label>
              </div>
            @endfor
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label"><i class="fas fa-comment-dots"></i> Saran / Masukan</label>
          <textarea name="saran" class="form-control" rows="3" placeholder="Tuliskan masukan Anda...">{{ $survey->saran }}</textarea>
        </div>

        <button type="submit" class="btn btn-submit w-100">
          <i class="fas fa-paper-plane"></i> Kirim Survey
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
