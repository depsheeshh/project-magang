@extends('layouts.admin')
@section('title','Permission Management')
@section('page-title','Permission Management')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Permission Management</h4>
  </div>
  <div class="card-body">
    <p class="mb-2">Kelola permission sistem. Klik tombol di bawah untuk menambah permission baru.</p>
    <button class="btn btn-primary" data-toggle="modal" data-target="#createPermissionModal">
      Tambah Permission
    </button>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Permission</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($permissions as $permission)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $permission->name }}</td>
          <td>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPermissionModal{{ $permission->id }}">
              <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('admin.permissions.destroy',$permission->id) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="3" class="text-center">Belum ada permission</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    @if ($permissions->hasPages())
  <div class="d-flex justify-content-end mt-3">
    {{ $permissions->links('pagination::bootstrap-4') }}
  </div>
@endif

  </div>
</div>
@endsection

{{-- ================== MODALS ================== --}}
@section('modals')
<!-- Modal Create -->
<div class="modal fade" id="createPermissionModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Permission</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Permission</label>
            <input type="text" name="name" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit untuk setiap permission -->
@foreach($permissions as $permission)
<div class="modal fade" id="editPermissionModal{{ $permission->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.permissions.update',$permission->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Permission</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Permission</label>
            <input type="text" name="name" value="{{ $permission->name }}" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
