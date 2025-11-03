@extends('layouts.admin')

@section('title', 'Data Instansi')
@section('page-title', 'Data Instansi')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Daftar Instansi</h4>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createInstansiModal">
      <i class="fas fa-plus-circle"></i> Tambah Instansi
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="thead-dark">
            <tr>
            <th>#</th>
            <th>Nama Instansi</th>
            <th>Alias</th>
            <th>Jenis</th>
            <th>Alamat / Lokasi</th>
            <th>Status</th>
            <th>Sumber</th>
            <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($instansi as $i)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $i->nama_instansi }}</td>
                <td>{{ $i->alias }}</td>
                <td>{{ ucfirst($i->jenis) }}</td>
                <td>{{ $i->lokasi ?? '-' }}</td>
                <td>
                @if($i->is_active)
                    <span class="badge badge-success">Aktif</span>
                @else
                    <span class="badge badge-secondary">Nonaktif</span>
                @endif
                </td>
                <td>
                @if($i->creator && $i->creator->hasRole('admin'))
                    <span class="badge badge-primary">Admin</span>
                @else
                    <span class="badge badge-success">Peserta</span>
                @endif
                </td>
                <td>
                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editInstansiModal{{ $i->id }}">
                    <i class="fas fa-edit"></i>
                </button>
                <form action="{{ route('admin.instansi.destroy', $i->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                    <i class="fas fa-trash"></i>
                    </button>
                </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted">Belum ada instansi</td></tr>
            @endforelse
        </tbody>
        </table>

      {{ $instansi->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah -->
<div class="modal fade" id="createInstansiModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.instansi.store') }}">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Instansi</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Instansi</label>
            <input type="text" name="nama_instansi" class="form-control" required>
          </div>
          <div class="form-group mt-2">
            <label>Alamat / Lokasi</label>
            <input type="text" name="lokasi" class="form-control">
          </div>
          <div class="form-group mt-2">
            <label>Alias</label>
            <input type="text" name="alias" class="form-control" required>
            </div>
            <div class="form-group mt-2">
            <label>Jenis</label>
            <select name="jenis" class="form-control" required>
                <option value="instansi">Instansi</option>
                <option value="kelurahan">Kelurahan</option>
                <option value="puskesmas">Puskesmas</option>
            </select>
            </div>
            <div class="form-group mt-2">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" selected>Aktif</option>
                <option value="0">Nonaktif</option>
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
@foreach($instansi as $i)
<div class="modal fade" id="editInstansiModal{{ $i->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.instansi.update', $i->id) }}">
        @csrf @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Instansi</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Instansi</label>
            <input type="text" name="nama_instansi" class="form-control" value="{{ $i->nama_instansi }}" required>
          </div>
          <div class="form-group mt-2">
            <label>Alamat / Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ $i->lokasi }}">
          </div>
          <div class="form-group mt-2">
            <label>Alias</label>
            <input type="text" name="alias" class="form-control" value="{{ $i->alias }}" required>
            </div>
            <div class="form-group mt-2">
            <label>Jenis</label>
            <select name="jenis" class="form-control" required>
                <option value="instansi" {{ $i->jenis=='instansi'?'selected':'' }}>Instansi</option>
                <option value="kelurahan" {{ $i->jenis=='kelurahan'?'selected':'' }}>Kelurahan</option>
                <option value="puskesmas" {{ $i->jenis=='puskesmas'?'selected':'' }}>Puskesmas</option>
            </select>
            </div>
            <div class="form-group mt-2">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $i->is_active ? 'selected':'' }}>Aktif</option>
                <option value="0" {{ !$i->is_active ? 'selected':'' }}>Nonaktif</option>
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
