@php
  $prefix = Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
@endphp

@extends('layouts.admin')

@section('title', 'Checkin Manual')
@section('page-title', 'Checkin Manual')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Daftar Peserta Rapat: {{ $rapat->judul }}</h4>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createPesertaModal">
      <i class="fas fa-plus-circle"></i> Tambah Peserta
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Instansi</th>
            <th>Status Kehadiran</th>
            <th>Jam Hadir</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rapat->undangan as $u)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $u->user->name ?? '-' }}</td>
            <td>{{ $u->jabatan ?? '-' }}</td>
            <td>{{ $u->instansi->nama_instansi ?? ($u->user->instansi->nama_instansi ?? '-') }}</td>
            <td>
              @switch($u->status_kehadiran)
                @case('pending') <span class="badge badge-warning">Belum Check-in</span> @break
                @case('hadir')   <span class="badge badge-success">Sudah Check-in</span> @break
                @case('selesai') <span class="badge badge-secondary">Selesai</span> @break
                @default         <span class="badge badge-danger">Tidak Hadir</span>
              @endswitch
            </td>
            <td>{{ $u->checked_in_at ? $u->checked_in_at->format('d/m/Y H:i') : '-' }}</td>
            <td>
              @if($u->status_kehadiran === 'pending')
                <form action="{{ route($prefix.'.rapat.peserta.checkin', [$rapat->id, $u->id]) }}" method="POST" class="d-inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-sign-in-alt"></i> Check-in
                  </button>
                </form>
              @elseif($u->status_kehadiran === 'hadir')
                <form action="{{ route($prefix.'.rapat.peserta.checkout', [$rapat->id, $u->id]) }}" method="POST" class="d-inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-secondary btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Checkout
                  </button>
                </form>
              @endif

              <!-- Tombol edit peserta -->
              <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPesertaModal{{ $u->id }}">
                <i class="fas fa-edit"></i>
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted">Belum ada peserta</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<a href="{{ route($prefix.'.rapat.index') }}" class="btn btn-secondary btn-sm">
  <i class="fas fa-arrow-left"></i> Kembali
</a>
@endsection

@section('modals')
<!-- Modal Tambah Peserta -->
<div class="modal fade" id="createPesertaModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route($prefix.'.rapat.peserta.store', $rapat->id) }}">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Peserta</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
          </div>
          <div class="form-group mt-2">
            <label>Jabatan</label>
            <input type="text" name="jabatan" class="form-control">
          </div>
          <div class="form-group mt-2">
            <label>Instansi</label>
            <select name="instansi_id" class="form-control" required>
              <option value="">-- Pilih Instansi --</option>
              @foreach($instansi as $i)
                <option value="{{ $i->id }}">{{ $i->nama_instansi }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group mt-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
            <small class="text-muted">Wajib, untuk sinkronisasi ke tabel users.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Peserta -->
@foreach($rapat->undangan as $u)
<div class="modal fade" id="editPesertaModal{{ $u->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route($prefix.'.rapat.peserta.update', [$rapat->id, $u->id]) }}">
        @csrf @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Peserta</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $u->user->name }}" required>
          </div>
          <div class="form-group mt-2">
            <label>Jabatan</label>
            <input type="text" name="jabatan" class="form-control" value="{{ $u->jabatan }}">
          </div>
          <div class="form-group mt-2">
            <label>Instansi</label>
            <select name="instansi_id" class="form-control" required>
              @foreach($instansi as $i)
                <option value="{{ $i->id }}" {{ $u->instansi_id == $i->id ? 'selected' : '' }}>
                  {{ $i->nama_instansi }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group mt-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $u->user->email }}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
