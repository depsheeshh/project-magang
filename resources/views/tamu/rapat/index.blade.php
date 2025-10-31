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
              @if($undangan->user->instansi_id)
                <button class="btn btn-sm btn-outline-primary ml-2 mb-2" data-toggle="modal" data-target="#updateInstansiModal{{ $rapat->id }}">
                  <i class="fas fa-edit"></i> Ganti Instansi
                </button>
              @endif
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

      {{-- Modal Update Instansi --}}
      @section('modals')
      <div class="modal fade" id="updateInstansiModal{{ $rapat->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <form method="POST" action="{{ route('tamu.rapat.updateInstansi') }}">
              @csrf
              <input type="hidden" name="rapat_id" value="{{ $rapat->id }}">
              <input type="hidden" name="mode" id="modeUpdate{{ $rapat->id }}" value="select">

              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-sync-alt"></i> Ganti Instansi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>

              <div class="modal-body">
                {{-- Toggle mode --}}
                <div class="btn-group btn-group-toggle mb-3 w-100" data-toggle="buttons">
                  <label class="btn btn-outline-primary active w-50" id="btnModeSelectUpdate{{ $rapat->id }}">
                    <input type="radio" checked> <i class="fas fa-list"></i> Pilih Instansi
                  </label>
                  <label class="btn btn-outline-secondary w-50" id="btnModeManualUpdate{{ $rapat->id }}">
                    <input type="radio"> <i class="fas fa-pen"></i> Isi Manual
                  </label>
                </div>

                {{-- Box pilih instansi --}}
                <div id="selectAdminBoxUpdate{{ $rapat->id }}" class="fade-slide show">
                  <div class="form-group">
                    <label for="instansi_admin_id_update{{ $rapat->id }}">
                      <i class="fas fa-search"></i> Cari & Pilih Instansi
                    </label>
                    <select name="instansi_admin_id" id="instansi_admin_id_update{{ $rapat->id }}" class="form-control">
                      <option value="">-- Pilih Instansi --</option>
                    </select>
                  </div>
                  <div class="row mt-2">
                    <div class="col-md-6">
                      <label>Nama Instansi</label>
                      <input type="text" id="namaInstansiPreviewUpdate{{ $rapat->id }}" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                      <label>Alamat / Lokasi</label>
                      <input type="text" id="lokasiPreviewUpdate{{ $rapat->id }}" class="form-control" readonly>
                    </div>
                  </div>
                </div>

                {{-- Box manual --}}
                <div id="manualBoxUpdate{{ $rapat->id }}" class="fade-slide d-none">
                  <div class="form-group">
                    <label for="nama_instansi_update{{ $rapat->id }}">
                      <i class="fas fa-building"></i> Nama Instansi
                    </label>
                    <input type="text" name="nama_instansi" id="nama_instansi_update{{ $rapat->id }}" class="form-control">
                  </div>
                  <div class="form-group">
                    <label for="lokasi_update{{ $rapat->id }}">
                      <i class="fas fa-map-marker-alt"></i> Lokasi
                    </label>
                    <input type="text" name="lokasi" id="lokasi_update{{ $rapat->id }}" class="form-control">
                  </div>
                </div>
              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      @endsection

    @empty
      <p class="text-muted">Anda belum memiliki undangan rapat.</p>
    @endforelse
  </div>
</div>
@endsection
