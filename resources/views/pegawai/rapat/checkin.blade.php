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
        {{-- 🚨 Checkout langsung berhasil --}}
        <form action="{{ route('pegawai.rapat.checkout',$rapat->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger mx-1">
            <i class="fas fa-sign-out-alt"></i> Checkout Rapat
        </button>
        </form>
    @elseif($undangan->status_kehadiran === 'selesai')
        {{-- 🚨 Jika rapat punya survey dan belum isi → tampil tombol opsional isi survey --}}
        @if($rapat->survey && $undangan->status_survey === 'belum_isi')
        <a href="{{ route('pegawai.rapat.scanSurvey',$rapat->id) }}" class="btn btn-success mx-3">
            <i class="fas fa-poll"></i> Isi Survey (Opsional)
        </a>
        @elseif($rapat->survey && $undangan->status_survey === 'sudah_isi')
        <span class="badge bg-success mt-2"><i class="fas fa-check"></i> Survey sudah diisi</span>
        @endif
    @endif
    </div>
  </div>
</div>
@endsection
