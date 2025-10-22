@extends('layouts.admin')

@section('title','Detail Rapat')
@section('page-title','Detail Rapat')

@section('content')

<div class="card mb-3">
  <div class="card-header">
    <h5>Tambah Undangan</h5>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.rapat.storeInvitation', $rapat->id) }}" method="POST">
      @csrf
      <div class="form-row">
        <div class="col-md-8">
          <select name="user_id" class="form-control" required>
            <option value="">-- Pilih User --</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}">
                {{ $user->name }} ({{ $user->instansi->nama_instansi ?? '-' }})
              </option>
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
  </div>
</div>

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
    </dl>
  </div>
</div>

@php
  $total   = $rapat->undangan->count();
  $hadir   = $rapat->undangan->where('status_kehadiran','hadir')->count();
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
  <div class="col-md-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-user-check fa-2x text-success mb-2"></i>
        <h5 class="card-title mb-1">Hadir</h5>
        <span class="badge badge-success">{{ $hadir }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-hourglass-half fa-2x text-secondary mb-2"></i>
        <h5 class="card-title mb-1">Pending</h5>
        <span class="badge badge-secondary">{{ $pending }}</span>
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

<div class="card">
  <div class="card-header">
    <h4>Daftar Undangan</h4>
  </div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama User</th>
          <th>Instansi Asal</th>
          <th>Status Kehadiran</th>
          <th>Waktu Check-in</th>
          <th>QR Code</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rapat->undangan as $undangan)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $undangan->user->name ?? '-' }}</td>
          <td>{{ $undangan->instansi->nama_instansi ?? '-' }}</td>
          <td>
            @if($undangan->status_kehadiran === 'hadir')
              <span class="badge badge-success">Hadir</span>
            @elseif($undangan->status_kehadiran === 'tidak_hadir')
              <span class="badge badge-danger">Tidak Hadir</span>
            @else
              <span class="badge badge-secondary">Pending</span>
            @endif
          </td>
          <td>{{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i:s') : '-' }}</td>
          <td>
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate(route('tamu.rapat.checkin.token', $undangan->checkin_token)) !!}
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
        <tr><td colspan="7" class="text-center">Belum ada undangan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<a href="{{ route('admin.rapat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
