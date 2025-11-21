@extends('layouts.guest')

@section('title','Terima Kasih')

@push('styles')
<style>
  body {
    background: linear-gradient(180deg, #f8fafc, #eef2ff);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .thanks-container {
    animation: fadeIn 0.7s ease;
    text-align: center;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 28px rgba(0,0,0,0.12);
    padding: 60px 40px;
    max-width: 600px;
    margin: 20px;
    transition: background 0.4s, color 0.4s;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .thanks-container i {
    color: #16a34a;
    font-size: 80px;
    margin-bottom: 20px;
    animation: pop 0.6s ease;
  }

  @keyframes pop {
    0% { transform: scale(0.7); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }

  .thanks-title {
    font-size: 28px;
    font-weight: 700;
    color: #1e3a8a;
    margin-bottom: 12px;
  }

  .thanks-text {
    color: #475569;
    font-size: 16px;
    margin-bottom: 30px;
    line-height: 1.6;
  }

  .btn-home, .btn-survey {
    border: none;
    border-radius: 12px;
    padding: 12px 24px;
    font-weight: 600;
    color: #fff;
    transition: 0.3s ease;
    margin: 5px;
    display: inline-block;
  }

  .btn-home {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
  }
  .btn-home:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
    transform: translateY(-2px);
  }

  .btn-survey {
    background: linear-gradient(135deg, #06b6d4, #0e7490);
  }
  .btn-survey:hover {
    background: linear-gradient(135deg, #0e7490, #0f766e);
    transform: translateY(-2px);
  }

  /* 🌙 Dark Mode */
  @media (prefers-color-scheme: dark) {
    body {
      background-color: #0f172a !important;
    }
    .thanks-container {
      background-color: #1e293b;
      color: #f1f5f9;
      box-shadow: 0 0 15px rgba(0,0,0,0.6);
    }
    .thanks-title { color: #93c5fd; }
    .thanks-text { color: #cbd5e1; }
    .btn-home {
      background: linear-gradient(135deg, #3b82f6, #1e40af);
    }
    .btn-survey {
      background: linear-gradient(135deg, #0ea5e9, #0369a1);
    }
  }
</style>
@endpush

@section('content')
<div class="thanks-container">
  <i class="fas fa-check-circle"></i>
  <div class="thanks-title">Terima Kasih!</div>
  <div class="thanks-text">
    Survey rapat <strong>{{ $survey->judul }}</strong> telah berhasil dikirim 🎉<br>
    Masukan Anda sangat berarti untuk peningkatan pelayanan kami di DKIS Kota Cirebon.
  </div>
  <a href="{{ url('/') }}" class="btn btn-home">
    <i class="fas fa-home me-1"></i> Kembali ke Beranda
  </a>
  @if($survey->tipe === 'Internal')
    <a href="{{ route('pegawai.survey.rapat.form.internal', $survey->slug) }}" class="btn btn-survey">
        <i class="fas fa-redo me-1"></i> Isi Lagi (Internal)
    </a>
    @else
    <a href="{{ route('tamu.survey.rapat.form.eksternal', $survey->slug) }}" class="btn btn-survey">
        <i class="fas fa-redo me-1"></i> Isi Lagi (Eksternal)
    </a>
    @endif
</div>
@endsection
