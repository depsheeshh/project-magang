@extends('layouts.admin')

@section('title','Data Bidang')
@section('page-title','Data Bidang')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Manajemen Bidang</h4>
  </div>
  <div class="card-body">
    <p class="mb-2">Kelola data bidang. Klik tombol di bawah untuk menambah bidang baru.</p>
    <button class="btn btn-primary" data-toggle="modal" data-target="#createBidangModal">
      Tambah Bidang
    </button>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Bidang</th>
          <th>Deskripsi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($bidang as $b)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $b->nama_bidang }}</td>
          <td>{{ $b->deskripsi }}</td>
          <td>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editBidangModal{{ $b->id }}">
              <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('admin.bidang.destroy',$b->id) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <input type="hidden" name="reason" value="Menghapus bidang {{ $b->nama_bidang }}">
              <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                <i class="fas fa-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $bidang->links() }}
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Create -->
<div class="modal fade" id="createBidangModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.bidang.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Bidang</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Bidang</label>
            <input type="text" name="nama_bidang" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <label>Alasan</label>
            <textarea name="reason" class="form-control"></textarea>
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
@foreach($bidang as $b)
<div class="modal fade" id="editBidangModal{{ $b->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.bidang.update',$b->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Bidang</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Bidang</label>
            <input type="text" name="nama_bidang" value="{{ $b->nama_bidang }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ $b->deskripsi }}</textarea>
          </div>
          <div class="form-group">
            <label>Alasan</label>
            <textarea name="reason" class="form-control"></textarea>
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
