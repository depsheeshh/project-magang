@php
  $prefix = Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
@endphp

@extends('layouts.admin')

@section('title', 'Data Kantor')
@section('page-title', 'Data Kantor')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Daftar Kantor</h4>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createKantorModal">
      <i class="fas fa-plus-circle"></i> Tambah Kantor
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Nama Kantor</th>
            <th>Alamat</th>
            <th>Koordinat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kantor as $k)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $k->nama_kantor }}</td>
            <td>{{ $k->alamat ?? '-' }}</td>
            <td>{{ $k->latitude }}, {{ $k->longitude }}</td>
            <td>
              <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editKantorModal{{ $k->id }}">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route($prefix.'.kantor.destroy', $k->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="5" class="text-center text-muted">Belum ada kantor</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah -->
<div class="modal fade" id="createKantorModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route($prefix.'.kantor.store') }}">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Kantor</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Kantor</label>
            <input type="text" name="nama_kantor" class="form-control" required>
          </div>
          <div class="form-group mt-2">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required></textarea>
          </div>
          <div class="row mt-2">
            <div class="col">
              <label>Latitude</label>
              <input type="text" name="latitude" class="form-control" required>
            </div>
            <div class="col">
              <label>Longitude</label>
              <input type="text" name="longitude" class="form-control" required>
            </div>
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

<!-- Modal Edit -->
@foreach($kantor as $k)
<div class="modal fade" id="editKantorModal{{ $k->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route($prefix.'.kantor.update', $k->id) }}">
        @csrf @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Kantor</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Kantor</label>
            <input type="text" name="nama_kantor" class="form-control" value="{{ $k->nama_kantor }}" required>
          </div>
          <div class="form-group mt-2">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" required>{{ $k->alamat }}</textarea>
          </div>
          <div class="row mt-2">
            <div class="col">
              <label>Latitude</label>
              <input type="text" name="latitude" class="form-control" value="{{ $k->latitude }}" required>
            </div>
            <div class="col">
              <label>Longitude</label>
              <input type="text" name="longitude" class="form-control" value="{{ $k->longitude }}" required>
            </div>
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
