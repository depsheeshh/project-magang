@extends('layouts.guest')

@section('title','Terima Kasih')

@push('styles')
<style>
  .thanks-container {
    animation: fadeIn 0.7s ease;
    text-align: center;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 24px rgba(0,0,0,0.08);
    padding: 60px 30px;
    max-width: 550px;
    margin: 0 auto;
    transition: background 0.4s, color 0.4s;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .thanks-container i {
    color: #16a34a;
    font-size: 70px;
    margin-bottom: 20px;
  }

  .thanks-title {
    font-size: 26px;
    font-weight: 700;
    color: #1e3a8a;
    margin-bottom: 10px;
  }

  .thanks-text {
    color: #475569;
    font-size: 15px;
    margin-bottom: 25px;
  }

  .btn-home {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border: none;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 600;
    color: #fff;
    transition: 0.3s ease;
  }
  .btn-home:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e3a8a);
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
    .btn-home:hover {
      background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
    }
  }
</style>
@endpush

@section('content')
<div class="thanks-container">
  <i class="fas fa-check-circle"></i>
  <div class="thanks-title">Terima Kasih!</div>
  <div class="thanks-text">
    Survey kepuasan Anda telah kami terima 🎉
    Masukan Anda sangat berarti untuk peningkatan pelayanan kami.
  </div>
  <a href="{{ url('/') }}" class="btn btn-home">
    <i class="fas fa-home me-1"></i> Kembali ke Beranda
  </a>
</div>
@endsection
