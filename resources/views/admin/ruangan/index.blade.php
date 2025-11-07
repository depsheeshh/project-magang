@php
  $prefix = Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
@endphp

@extends('layouts.admin')

@section('title', 'Data Ruangan')
@section('page-title', 'Data Ruangan')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Daftar Ruangan</h4>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createRuanganModal">
      <i class="fas fa-plus-circle"></i> Tambah Ruangan
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Nama Ruangan</th>
            <th>Kantor</th>
            <th>Kapasitas</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($ruangan as $r)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $r->nama_ruangan }}</td>
            <td>{{ $r->kantor->nama_kantor ?? '-' }}</td>
            <td>{{ $r->kapasitas_maksimal }}</td>
            <td>
              @if($r->dipakai)
                <span class="badge badge-success">Dipakai</span>
              @else
                <span class="badge badge-secondary">Kosong</span>
              @endif
            </td>
            <td>
              <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editRuanganModal{{ $r->id }}">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route($prefix.'.ruangan.destroy', $r->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted">Belum ada ruangan</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah -->
<div class="modal fade" id="createRuanganModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route($prefix.'.ruangan.store') }}">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Ruangan</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Ruangan</label>
            <input type="text" name="nama_ruangan" class="form-control" required>
          </div>
          <div class="form-group mt-2">
            <label>Kantor</label>
            <select name="id_kantor" class="form-control" required>
              <option value="">-- Pilih Kantor --</option>
              @foreach($kantor as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kantor }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group mt-2">
            <label>Kapasitas Maksimal</label>
            <input type="number" name="kapasitas_maksimal" class="form-control">
          </div>
          <div class="form-group mt-2">
            <label>Status</label>
            <select name="dipakai" class="form-control">
              <option value="0">Kosong</option>
              <option value="1">Dipakai</option>
            </select>
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
@foreach($ruangan as $r)
<div class="modal fade" id="editRuanganModal{{ $r->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route($prefix.'.ruangan.update', $r->id) }}">
        @csrf @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Ruangan</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Ruangan</label>
            <input type="text" name="nama_ruangan" class="form-control" value="{{ $r->nama_ruangan }}" required>
          </div>
          <div class="form-group mt-2">
            <label>Kantor</label>
            <select name="id_kantor" class="form-control" required>
              <option value="">-- Pilih Kantor --</option>
              @foreach($kantor as $k)
                <option value="{{ $k->id }}" {{ $r->id_kantor == $k->id ? 'selected' : '' }}>
                  {{ $k->nama_kantor }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group mt-2">
            <label>Kapasitas Maksimal</label>
            <input type="number" name="kapasitas_maksimal" class="form-control" value="{{ $r->kapasitas_maksimal }}">
          </div>
          <div class="form-group mt-2">
            <label>Status</label>
            <select name="dipakai" class="form-control">
              <option value="0" {{ !$r->dipakai ? 'selected' : '' }}>Kosong</option>
              <option value="1" {{ $r->dipakai ? 'selected' : '' }}>Dipakai</option>
            </select>
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
