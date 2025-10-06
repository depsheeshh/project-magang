@extends('layouts.admin')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h4>Detail User</h4>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th>Nama</th>
            <td>{{ $user->name }}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
          </tr>
          <tr>
            <th>Role</th>
            <td>
              @forelse($user->roles as $role)
                <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
              @empty
                <span class="text-muted">Belum ada role</span>
              @endforelse
            </td>
          </tr>
          <tr>
            <th>Status Akun</th>
            <td>
              @if($user->status)
                <span class="badge badge-success">Active</span>
              @else
                <span class="badge badge-danger">Inactive</span>
              @endif
            </td>
          </tr>
          <tr>
            <th>Verifikasi Email</th>
            <td>
              @if($user->email_verified_at)
                <span class="badge badge-success">Terverifikasi</span>
                <br>
                <small class="text-muted">pada {{ $user->email_verified_at->format('d M Y H:i') }}</small>
              @else
                <span class="badge badge-danger">Belum Verifikasi</span>
              @endif
            </td>
          </tr>
          <tr>
            <th>Dibuat</th>
            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
          </tr>
          <tr>
            <th>Terakhir Update</th>
            <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
          </tr>
        </table>
      </div>
      <div class="card-footer text-right">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>
@endsection
