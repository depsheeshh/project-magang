@extends('layouts.admin')

@section('title', 'Data User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4>Daftar User</h4>
        <div class="card-header-action">
          <!-- Tombol trigger modal create -->
          <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
            <i class="fas fa-plus"></i> Tambah User
          </button>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-md">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Dibuat</th>
                <th>Status</th>
                <th>Verifikasi Email</th> <!-- kolom baru -->
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($users as $index => $user)
              <tr>
                <td>{{ $users->firstItem() + $index }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  @foreach($user->roles as $role)
                    <div class="badge badge-info">{{ ucfirst($role->name) }}</div>
                  @endforeach
                </td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>
                  @if($user->status)
                    <div class="badge badge-success">Active</div>
                  @else
                    <div class="badge badge-danger">Inactive</div>
                  @endif
                </td>
                <td>
                    @if($user->email_verified_at)
                    <div class="badge badge-success">Terverifikasi</div>
                    @else
                    <div class="badge badge-danger">Belum Verifikasi</div>
                    @endif
                </td>
                <td>
                  <!-- Tombol trigger modal edit -->
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                    </a>
                  <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editUserModal{{ $user->id }}">
                    <i class="fas fa-edit"></i>
                  </button>
                  <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus user ini?')">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-center">Belum ada user</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-footer text-right">
        @if ($users->hasPages())
          {{ $users->links('pagination::bootstrap-4') }}
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

{{-- ================== MODALS ================== --}}
@section('modals')
@php
  $isCreateError = old('_action') === 'create';
@endphp

<!-- Modal Create User -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <input type="hidden" name="_action" value="create">

        <div class="modal-header">
          <h5 class="modal-title">Tambah User</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          {{-- Nama --}}
          <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name"
                   value="{{ $isCreateError ? old('name') : '' }}"
                   class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Email --}}
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="{{ $isCreateError ? old('email') : '' }}"
                   class="form-control @error('email') is-invalid @enderror" required autocomplete="off">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Password --}}
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Role --}}
          <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role"
                    class="form-control @error('role') is-invalid @enderror" required>
              <option value="">-- Pilih Role --</option>
              @foreach($roles as $role)
                <option value="{{ $role->name }}"
                  {{ $isCreateError && old('role') == $role->name ? 'selected' : '' }}>
                  {{ ucfirst($role->name) }}
                </option>
              @endforeach
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Status --}}
          <div class="form-group">
            <label for="status">Status</label>
            @php
              $selectedStatus = $isCreateError ? old('status', 1) : 1;
            @endphp
            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
              <option value="1" {{ (int)$selectedStatus === 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ (int)$selectedStatus === 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

<!-- Modal Edit untuk setiap user -->
@foreach($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <input type="hidden" name="_action" value="edit">
        <input type="hidden" name="_user_id" value="{{ $user->id }}">

        <div class="modal-header">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          @php
            $isEditError = old('_action') === 'edit' && old('_user_id') == $user->id;
          @endphp

          {{-- Nama --}}
          <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name"
                   value="{{ $isEditError ? old('name') : $user->name }}"
                   class="form-control @error('name') is-invalid @enderror" required>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Email --}}
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email"
                   value="{{ $isEditError ? old('email') : $user->email }}"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Password Lama --}}
            <div class="form-group">
            <label>Password Lama</label>
            <input type="password" name="old_password"
                    class="form-control @error('old_password') is-invalid @enderror"
                    placeholder="Isi jika ingin mengganti password">
            @error('old_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Password Baru --}}
            <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="new_password"
                    class="form-control @error('new_password') is-invalid @enderror"
                    placeholder="Kosongkan jika tidak ingin mengubah">
            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div class="form-group">
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation"
                    class="form-control"
                    placeholder="Ulangi password baru">
            </div>


          {{-- Role --}}
          <div class="form-group">
            <label>Role</label>
            @php
              $currentRole = $user->getRoleNames()->first();
              $selectedRole = $isEditError ? old('role') : $currentRole;
            @endphp
            <select name="role" class="form-control @error('role') is-invalid @enderror" required>
              @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ $selectedRole === $role->name ? 'selected' : '' }}>
                  {{ ucfirst($role->name) }}
                </option>
              @endforeach
            </select>
            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          {{-- Status --}}
          <div class="form-group">
            <label>Status</label>
            @php
              $selectedStatus = $isEditError ? old('status', $user->status ?? 1) : ($user->status ?? 1);
            @endphp
            <select name="status" class="form-control @error('status') is-invalid @enderror">
              <option value="1" {{ (int)$selectedStatus === 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ (int)$selectedStatus === 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
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



{{-- Script untuk auto-open modal jika validasi gagal --}}
@if ($errors->any())
<script>
  $(document).ready(function() {
    // kalau error berasal dari create
    @if(old('_action') === 'create')
      $('#createUserModal').modal('show');
    @endif

    // kalau error berasal dari edit
    @if(old('_action') === 'edit' && old('_user_id'))
      $('#editUserModal{{ old('_user_id') }}').modal('show');
    @endif
  });
</script>
@endif
