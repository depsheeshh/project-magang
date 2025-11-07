@extends('layouts.admin')

@section('title','Rapat Saya')
@section('page-title','Rapat Saya')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Daftar Rapat Saya</h4>
  </div>
  <div class="card-body">
    @forelse($rapatSaya as $rapat)
      @php $undangan = $rapat->undangan->first(); @endphp

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
              <strong>Instansi:</strong> {{ $undangan->user->instansi->nama_instansi ?? '-' }}
              <br>
              <strong>Status Kehadiran:</strong>
              @if($undangan->status_kehadiran === 'pending' || $undangan->status_kehadiran === null)
                <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Belum Check-in</span>
              @elseif($undangan->status_kehadiran === 'hadir')
                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Sudah Check-in</span>
                <small class="text-muted d-block">
                  {{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i') : '' }}
                </small>
              @elseif($undangan->status_kehadiran === 'selesai')
                <span class="badge bg-secondary"><i class="fas fa-flag-checkered"></i> Selesai</span>
                <small class="text-muted d-block">
                  {{ $undangan->checked_out_at ? $undangan->checked_out_at->format('d-m-Y H:i') : '' }}
                </small>
              @else
                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Tidak Hadir</span>
              @endif
            </p>
          @endif

          {{-- Aksi --}}
          @if($undangan && ($undangan->status_kehadiran === 'pending' || $undangan->status_kehadiran === null))
            <a href="{{ route('tamu.rapat.show', $rapat->id) }}" class="btn btn-info btn-sm">
              <i class="fas fa-info-circle"></i> Detail & Check-in
            </a>
          @elseif($undangan && $undangan->status_kehadiran === 'hadir')
            <form action="{{ route('tamu.rapat.checkout',$rapat->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Checkout
              </button>
            </form>
          @endif
        </div>
      </div>


    @empty
      <p class="text-muted">Anda belum memiliki undangan rapat.</p>
    @endforelse
  </div>
</div>
@endsection
