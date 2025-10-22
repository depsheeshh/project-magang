@extends('layouts.admin')
@section('title','Manajemen Jabatan')
@section('page-title','Manajemen Jabatan')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Manajemen Jabatan</h4>
  </div>
  <div class="card-body">
    <p class="mb-2">Kelola data jabatan. Klik tombol di bawah untuk menambah jabatan baru.</p>
    <button class="btn btn-primary" data-toggle="modal" data-target="#createJabatanModal">
      Tambah Jabatan
    </button>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Jabatan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($jabatan as $j)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $j->nama_jabatan }}</td>
          <td>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editJabatanModal{{ $j->id }}">
              <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('admin.jabatan.destroy',$j->id) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $jabatan->links('pagination::bootstrap-5') }}
  </div>
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Create -->
<div class="modal fade" id="createJabatanModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.jabatan.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Jabatan</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Jabatan</label>
            <input type="text" name="nama_jabatan" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
@foreach($jabatan as $j)
<div class="modal fade" id="editJabatanModal{{ $j->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.jabatan.update',$j->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Jabatan</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Jabatan</label>
            <input type="text" name="nama_jabatan" value="{{ $j->nama_jabatan }}" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
