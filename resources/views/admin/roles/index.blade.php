@extends('layouts.admin')
@section('title','Role Management')
@section('page-title','Role Management')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Role Management</h4>
  </div>
  <div class="card-body">
    <p class="mb-2">Kelola role dan permission dengan mudah. Klik tombol di bawah untuk menambah role baru.</p>
    <!-- Tombol trigger modal create -->
    <button class="btn btn-primary" data-toggle="modal" data-target="#createRoleModal">
      Tambah Role
    </button>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Permissions</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($roles as $role)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $role->name }}</td>
          <td>
            @foreach($role->permissions as $perm)
              <span class="badge badge-info">{{ $perm->name }}</span>
            @endforeach
          </td>
          <td>

            <!-- Tombol trigger modal edit -->
            <a href="{{ route('admin.roles.show', $role->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editRoleModal{{ $role->id }}">
              <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('admin.roles.destroy',$role->id) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $roles->links() }}
  </div>
</div>
@endsection

{{-- ================== MODALS (Pindah ke bawah, di luar section) ================== --}}
@section('modals')
<!-- Modal Create -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Role</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Role</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Permissions</label><br>
            @foreach($permissions as $perm)
              <div class="form-check form-check-inline">
                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="form-check-input">
                <label class="form-check-label">{{ $perm->name }}</label>
              </div>
            @endforeach
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

<!-- Modal Edit untuk setiap role -->
@foreach($roles as $role)
<div class="modal fade" id="editRoleModal{{ $role->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.roles.update',$role->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Role</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Role</label>
            <input type="text" name="name" value="{{ $role->name }}" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Permissions</label><br>
            @foreach($permissions as $perm)
              <div class="form-check form-check-inline">
                <input type="checkbox" name="permissions[]" value="{{ $perm->name }}"
                  class="form-check-input"
                  {{ in_array($perm->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $perm->name }}</label>
              </div>
            @endforeach
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
