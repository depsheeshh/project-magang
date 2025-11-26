@extends('layouts.admin')

@section('title','Rapat Saya')
@section('page-title','Rapat Saya')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4>Daftar Rapat Saya</h4>
    <a href="{{ route('tamu.rapat.scan.dashboard') }}" class="btn btn-primary btn-sm">
      <i class="fas fa-qrcode"></i> Scan QR Rapat Eksternal
    </a>
  </div>

  <div class="card-body">
    {{-- Alert global dari session --}}
    @if(session('warning'))
      <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    @endif

    @forelse($rapatSaya as $rapat)
      @php
        // undangan untuk user ini (sudah difilter di controller)
        $undangan = $rapat->undangan->first();
        $survey   = $rapat->survey;
      @endphp

      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <h5 class="mb-2">{{ $rapat->judul }}</h5>
          <p class="mb-1">
            <span class="badge bg-info">
              {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
            </span>
            s/d
            <span class="badge bg-secondary">
              {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
            </span>
          </p>
          <p class="mb-3">
            Lokasi: {{ $rapat->lokasi ?? '-' }} <br>
            <small class="text-muted">
              Lat: {{ $rapat->latitude ?? '-' }},
              Lon: {{ $rapat->longitude ?? '-' }},
              Radius: {{ $rapat->radius ?? '-' }} m
            </small>
          </p>

          {{-- Data peserta diri sendiri --}}
          @if($undangan)
            <p class="mb-2">
              <strong>Peserta:</strong> {{ $undangan->user->name }} <br>
              <strong>Instansi:</strong> {{ $undangan->instansi->nama_instansi ?? '-' }} <br>
              <strong>Status Kehadiran:</strong>
              @if($undangan->status_kehadiran === 'pending' || $undangan->status_kehadiran === null)
                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Belum Check-in</span>
              @elseif($undangan->status_kehadiran === 'hadir')
                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Sudah Check-in</span>
                <small class="text-muted d-block">
                  {{ optional($undangan->checked_in_at)->format('d-m-Y H:i') }}
                </small>
              @elseif($undangan->status_kehadiran === 'selesai')
                <span class="badge bg-secondary"><i class="fas fa-flag-checkered"></i> Selesai</span>
                <small class="text-muted d-block">
                  {{ optional($undangan->checked_out_at)->format('d-m-Y H:i') }}
                </small>
                {{-- Status survey badge saat selesai --}}
                @if($survey)
                  @if($undangan->status_survey === 'sudah_isi')
                    <span class="badge bg-success mt-2"><i class="fas fa-check"></i> Survey sudah diisi</span>
                  @else
                    <span class="badge bg-warning text-dark mt-2"><i class="fas fa-clock"></i> Survey belum diisi</span>
                  @endif
                @endif
              @else
                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tidak Hadir</span>
              @endif
            </p>
          @endif

          {{-- Aksi --}}
          @if($undangan)
            @if($undangan->status_kehadiran === 'pending' || $undangan->status_kehadiran === null)
                <a href="{{ route('tamu.rapat.show', $rapat->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-info-circle"></i> Detail & Check-in
                </a>
            @elseif($undangan->status_kehadiran === 'hadir')
                <form action="{{ route('tamu.rapat.checkout',$rapat->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Checkout & Isi Survey
                </button>
                </form>
            @elseif($undangan->status_kehadiran === 'selesai' && $survey)
                {{-- Fallback: Scan QR Survey manual --}}
                @if($undangan->status_survey === 'belum_isi')
                <a href="{{ route('tamu.rapat.scan.survey.eksternal', $rapat->id) }}" class="btn btn-success btn-sm mt-2">
                    <i class="fas fa-qrcode"></i> Scan QR Survey (Opsional)
                </a>
                @endif
            @endif
            @endif
        </div>
      </div>
    @empty
      <p class="text-muted">Anda belum memiliki undangan rapat.</p>
    @endforelse
  </div>
</div>
@endsection
