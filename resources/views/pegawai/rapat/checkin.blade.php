@extends('layouts.admin')

@section('title','Detail Rapat')
@section('page-title','Detail Rapat')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>{{ $rapat->judul }}</h4>
  </div>
  <div class="card-body">
    <p><strong>Waktu:</strong>
      {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
      s/d
      {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
    </p>
    <p><strong>Lokasi:</strong> {{ $rapat->lokasi ?? '-' }}</p>
    <p><strong>Status Kehadiran:</strong>
      @if($undangan->status_kehadiran === null || $undangan->status_kehadiran === 'pending')
        <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Belum Check-in</span>
      @elseif($undangan->status_kehadiran === 'hadir')
        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Sudah Check-in</span>
        <small class="text-muted d-block">
          {{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i') : '' }}
        </small>
      @elseif($undangan->status_kehadiran === 'selesai')
        <span class="badge bg-secondary"><i class="fas fa-flag-checkered"></i> Selesai</span>
      @else
        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tidak Hadir</span>
      @endif
    </p>

    {{-- Instruksi check-in --}}
    @if($undangan->status_kehadiran === null || $undangan->status_kehadiran === 'pending')
      <p class="text-muted">
        Untuk check-in, silakan <strong>scan QR code rapat</strong> yang ditampilkan oleh admin di lokasi rapat.
      </p>
    @endif

    {{-- ✅ Tombol aksi horizontal --}}
    <div class="d-flex flex-wrap gap-2 mt-3">
      <a href="{{ route('pegawai.agenda.rapat') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>

      @if($undangan->status_kehadiran === 'hadir')
        @if($rapat->survey)
          {{-- Jika rapat punya survey → scan survey untuk checkout --}}
          <a href="{{ route('pegawai.rapat.scanSurvey',$rapat->id) }}" class="btn btn-success mx-1">
            <i class="fas fa-qrcode"></i> Scan QR Survey (Auto-Checkout)
          </a>
        @else
          {{-- Jika rapat tidak punya survey → checkout manual --}}
          <form action="{{ route('pegawai.rapat.checkout',$rapat->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary mx-1">
              <i class="fas fa-sign-out-alt"></i> Checkout Manual
            </button>
          </form>
        @endif
      @elseif($undangan->status_kehadiran === 'selesai')
        {{-- ✅ Jika rapat punya survey tapi belum isi, tetap tampil tombol scan survey --}}
        @if($rapat->survey && $undangan->status_survey === 'belum_isi')
          <a href="{{ route('pegawai.rapat.scanSurvey',$rapat->id) }}" class="btn btn-success mx-1">
            <i class="fas fa-qrcode"></i> Scan QR Survey
        </a>
        @endif
      @endif
    </div>
  </div>
</div>
@endsection
