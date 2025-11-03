@extends('layouts.admin')

@section('title','Detail Rapat')
@section('page-title','Detail Rapat - ' . $rapat->jenis_rapat)

@section('content')

<div class="card mb-3">
  <div class="card-header">
    <h5>Tambah Undangan</h5>
  </div>
  <div class="card-body">

    @if($rapat->jenis_rapat === 'Internal')
      {{-- Form undangan untuk pegawai DKIS --}}
      <form action="{{ route('admin.rapat.storeInvitation', $rapat->id) }}" method="POST">
        @csrf
        <div class="form-row">
          <div class="col-md-8">
            <select name="user_id" class="form-control" required>
              <option value="">-- Pilih Pegawai DKIS --</option>
              @foreach($users as $user)
                @if($user->hasRole('pegawai'))
                  <option value="{{ $user->id }}">
                    {{ $user->name }}
                  </option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-user-plus"></i> Tambah
            </button>
          </div>
        </div>
      </form>

      <hr>
      {{-- Bulk invite semua pegawai --}}
      <form action="{{ route('admin.rapat.inviteAll', $rapat->id) }}" method="POST">
        @csrf
        <input type="hidden" name="role" value="pegawai">
        <button type="submit" class="btn btn-sm btn-success">
          <i class="fas fa-user-tie"></i> Tambahkan Semua Pegawai
        </button>
      </form>

    @elseif($rapat->jenis_rapat === 'Eksternal')
        {{-- Form undangan untuk instansi --}}
        <form action="{{ route('admin.rapat.storeInvitationInstansi', $rapat->id) }}" method="POST">
            @csrf
            <div class="form-row">
            <div class="col-md-8">
                <select name="instansi_id" class="form-control" required>
                <option value="">-- Pilih Instansi --</option>
                @foreach($instansi as $i)
                    {{-- pastikan instansi belum diundang --}}
                    @if(!$rapat->undangan->where('instansi_id', $i->id)->count())
                    <option value="{{ $i->id }}">{{ $i->nama_instansi }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                <i class="fas fa-building"></i> Tambah Instansi
                </button>
            </div>
            </div>
        </form>

        <hr>
        {{-- Bulk invite semua instansi --}}
        <form action="{{ route('admin.rapat.inviteAllInstansi', $rapat->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">
            <i class="fas fa-university"></i> Tambahkan Semua Instansi
            </button>
        </form>
        @endif
  </div>
</div>



{{-- Detail rapat --}}
<div class="card mb-3">
  <div class="card-header">
    <h4>Detail Rapat</h4>
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Judul</dt>
      <dd class="col-sm-9">{{ $rapat->judul }}</dd>

      <dt class="col-sm-3">Waktu</dt>
      <dd class="col-sm-9">
        {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
        s/d
        {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
      </dd>

      <dt class="col-sm-3">Status</dt>
      <dd class="col-sm-9">
        @if($rapat->status === 'selesai')
          <span class="badge badge-success">Selesai</span>
        @elseif($rapat->status === 'berjalan')
          <span class="badge badge-primary">Sedang Berjalan</span>
        @elseif($rapat->status === 'dibatalkan')
          <span class="badge badge-secondary">Dibatalkan</span>
        @endif
      </dd>

      <dt class="col-sm-3">Jenis Rapat</dt>
      <dd class="col-sm-9">{{ $rapat->jenis_rapat ?? '-' }}</dd>

      <dt class="col-sm-3">Lokasi</dt>
      <dd class="col-sm-9">{{ $rapat->lokasi ?? '-' }}</dd>

      <dt class="col-sm-3">Koordinat</dt>
      <dd class="col-sm-9">
        Lat: {{ $rapat->latitude ?? '-' }},
        Lon: {{ $rapat->longitude ?? '-' }},
        Radius: {{ $rapat->radius ?? '-' }} m
      </dd>

      <dt class="col-sm-3">Jumlah Tamu</dt>
      <dd class="col-sm-9">{{ $rapat->jumlah_tamu ?? 0 }}</dd>

      <dt class="col-sm-3">QR Code Rapat</dt>
        <dd class="col-sm-9">
        @if($rapat->qr_token_hash)
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($rapat->qr_token_hash) !!}
        @else
            <span class="badge badge-warning">QR belum digenerate</span>
        @endif
        </dd>

    </dl>

    @hasanyrole('admin')
      <div class="mt-3 d-flex">
        @if($rapat->status === 'berjalan')
          <form action="{{ route('rapat.end', $rapat->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin mengakhiri rapat ini sekarang? Semua peserta hadir akan ditandai selesai.')"
            class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger btn-sm mr-2">
              <i class="fas fa-stop-circle"></i> Akhiri Rapat
            </button>
          </form>
        @endif

        <a href="{{ route('admin.rapat.export.csv', $rapat->id) }}" class="btn btn-success btn-sm mr-2">
          <i class="fas fa-file-csv"></i> Export CSV
        </a>
        <a href="{{ route('admin.rapat.export.pdf', $rapat->id) }}" class="btn btn-danger btn-sm">
          <i class="fas fa-file-pdf"></i> Export PDF
        </a>
      </div>
    @endhasanyrole
  </div>
</div>

{{-- Statistik --}}
@php
  $total   = $rapat->undangan->count();
  $hadir   = $rapat->undangan->where('status_kehadiran','hadir')->count();
  $selesai = $rapat->undangan->where('status_kehadiran','selesai')->count();
  $pending = $rapat->undangan->where('status_kehadiran','pending')->count();
  $tidak   = $rapat->undangan->where('status_kehadiran','tidak_hadir')->count();
@endphp

<div class="row mb-4">
  <div class="col-md-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-users fa-2x text-dark mb-2"></i>
        <h5 class="card-title mb-1">Total</h5>
        <span class="badge badge-dark">{{ $total }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-user-check fa-2x text-success mb-2"></i>
        <h5 class="card-title mb-1">Hadir</h5>
        <span class="badge badge-success">{{ $hadir }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-flag-checkered fa-2x text-secondary mb-2"></i>
        <h5 class="card-title mb-1">Selesai</h5>
        <span class="badge badge-secondary">{{ $selesai }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
        <h5 class="card-title mb-1">Pending</h5>
        <span class="badge badge-warning">{{ $pending }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-user-times fa-2x text-danger mb-2"></i>
        <h5 class="card-title mb-1">Tidak Hadir</h5>
        <span class="badge badge-danger">{{ $tidak }}</span>
      </div>
    </div>
  </div>
</div>

{{-- Daftar Undangan --}}
<div class="card">
    <div class="card-header">
        <h4>Daftar Undangan</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                @if($rapat->jenis_rapat === 'Internal')
                <th>Nama User</th>
                <th>Instansi Asal</th>
                @else
                <th>Nama Instansi</th>
                <th>Jumlah User</th>
                @endif
                <th>Status Kehadiran</th>
                <th>Waktu Check-in</th>
                <th>Waktu Check-out</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rapat->undangan as $undangan)
                <tr>
                <td>{{ $loop->iteration }}</td>

                @if($rapat->jenis_rapat === 'Internal')
                    <td>{{ $undangan->user->name ?? '-' }}</td>
                    <td>{{ $undangan->user->pegawai->instansi->nama_instansi ?? '-' }}</td>
                @else
                    <td>{{ $undangan->instansi->nama_instansi ?? '-' }}</td>
                    <td>{{ $undangan->instansi->users->count() ?? 0 }}</td>
                @endif

                <td>
                    @if($undangan->status_kehadiran === 'hadir')
                    <span class="badge badge-success">Hadir</span>
                    @elseif($undangan->status_kehadiran === 'selesai')
                    <span class="badge badge-secondary">Selesai</span>
                    @elseif($undangan->status_kehadiran === 'tidak_hadir')
                    <span class="badge badge-danger">Tidak Hadir</span>
                    @else
                    <span class="badge badge-warning text-dark">Pending</span>
                    @endif
                </td>

                <td>{{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i:s') : '-' }}</td>
                <td>
                    @if($undangan->checked_out_at)
                    {{ $undangan->checked_out_at->format('d-m-Y H:i:s') }}
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('admin.rapat.destroyInvitation', [$rapat->id, $undangan->id]) }}" method="POST" onsubmit="return confirm('Hapus undangan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                    </form>
                </td>
                </tr>
            @empty
                <tr>
                <td colspan="7" class="text-center">Belum ada undangan</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        </div>
    </div>
    </div>


<a href="{{ route('admin.rapat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
