@extends('layouts.guest')

@section('title', 'Isi Survey Rapat')

@push('styles')
<style>
    body {
        background: #f1f5f9;
    }

    /* Card utama */
    .survey-card {
        background: #ffffff;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        max-width: 760px;
        margin: auto;
        transition: 0.3s ease;
    }

    .survey-card:hover {
        box-shadow: 0 12px 28px rgba(0,0,0,0.08);
    }

    /* Judul */
    .survey-title {
        font-size: 26px;
        font-weight: 700;
        color: #1e3a8a;
        text-align: center;
    }

    .survey-sub {
        text-align: center;
        color: #475569;
        margin-bottom: 25px;
    }

    /* Label modern */
    .form-group label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
    }

    /* Input & Textarea neumorphism */
    .form-control {
        border-radius: 10px;
        border: 1px solid #d1d5db;
        padding: 12px;
        transition: 0.2s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
    }

    /* Radio styling */
    .radio-option {
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid #dce1e8;
        cursor: pointer;
        transition: .2s ease;
        margin-bottom: 8px;
        background: #ffffff;
    }

    .radio-option:hover {
        background: #f8fafc;
        box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
    }

    /* Submit Button */
    .btn-submit {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        border: none;
        padding: 12px 22px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        transition: 0.25s;
        width: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(37,99,235,0.4);
    }
</style>
@endpush


@section('content')
<div class="container mt-4 mb-5">
  <div class="survey-card" data-aos="fade-up">
    <h3 class="survey-title">Survey Rapat: {{ $survey->judul }}</h3>
    <p class="survey-sub">Tipe: {{ $survey->tipe }}</p>

    {{-- ✅ Alert feedback --}}
    @if(session('success'))
      <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif
    @if(session('warning'))
      <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger"><i class="fas fa-times-circle"></i> {{ session('error') }}</div>
    @endif

    {{-- ✅ Info khusus internal --}}
    @if($survey->tipe === 'Internal')
      <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Anda telah otomatis <strong>checkout</strong> dari rapat ini.
        Silakan isi survey untuk melengkapi evaluasi.
      </div>
    @endif

    {{-- 🔑 Form action sesuai tipe --}}
    @if($survey->tipe === 'Internal')
      <form action="{{ route('pegawai.survey.rapat.submit.internal', $survey->slug) }}" method="POST">
    @else
      <form action="{{ route('tamu.survey.rapat.submit.eksternal', $survey->slug) }}" method="POST">
    @endif
        @csrf

        {{-- Nama --}}
        <div class="form-group">
          <label>Nama <span class="text-danger">*</span></label>
          <input type="text" name="nama" class="form-control" required>
        </div>

        {{-- Instansi (hanya eksternal) --}}
        @if($survey->tipe === 'Eksternal')
          <div class="form-group mt-2">
            <label>Instansi <span class="text-danger">*</span></label>
            <select name="instansi" id="instansiSelect" class="form-control" required>
              <option value="">-- Pilih Instansi --</option>
              @foreach($instansi as $i)
                <option value="{{ $i->nama_instansi }}">{{ $i->nama_instansi }}</option>
              @endforeach
              <option value="lainnya">Lainnya...</option>
            </select>
          </div>
          <div class="form-group mt-2" id="instansiManualWrapper" style="display:none;">
            <label>Instansi (Manual)</label>
            <input type="text" name="instansi_manual" class="form-control" placeholder="Masukkan nama instansi">
          </div>
        @endif

        {{-- Pertanyaan 1 --}}
        <div class="form-group mt-3">
          <label><strong>Bagaimana kualitas rapat ini?</strong></label>
          <label class="radio-option d-block">
            <input type="radio" name="kualitas_rapat" value="Sangat Baik" required> <span class="ml-1">Sangat Baik</span>
          </label>
          <label class="radio-option d-block">
            <input type="radio" name="kualitas_rapat" value="Baik"> <span class="ml-1">Baik</span>
          </label>
          <label class="radio-option d-block">
            <input type="radio" name="kualitas_rapat" value="Cukup"> <span class="ml-1">Cukup</span>
          </label>
          <label class="radio-option d-block">
            <input type="radio" name="kualitas_rapat" value="Kurang"> <span class="ml-1">Kurang</span>
          </label>
        </div>

        {{-- Opini --}}
        <div class="form-group mt-3">
          <label><strong>Opini atau pendapat Anda:</strong></label>
          <textarea name="opini" class="form-control" rows="3" placeholder="Tuliskan pendapat Anda..."></textarea>
        </div>

        {{-- Saran --}}
        <div class="form-group mt-3">
          <label><strong>Saran untuk rapat berikutnya:</strong></label>
          <textarea name="saran" class="form-control" rows="3" placeholder="Tuliskan saran..."></textarea>
        </div>

        <button type="submit" class="btn btn-submit mt-4">
          <i class="fas fa-paper-plane mr-1"></i> Kirim Jawaban
        </button>
      </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
  const select = document.getElementById('instansiSelect');
  const manualWrapper = document.getElementById('instansiManualWrapper');
  if (select) {
    select.addEventListener('change', function() {
      manualWrapper.style.display = (this.value === 'lainnya') ? 'block' : 'none';
    });
  }
});
</script>
@endpush
