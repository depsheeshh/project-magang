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
              @foreach($user->roles as $role)
                <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
              @endforeach
            </td>
          </tr>
          <tr>
            <th>Status</th>
            <td>
              @if($user->status)
                <span class="badge badge-success">Active</span>
              @else
                <span class="badge badge-danger">Inactive</span>
              @endif
            </td>
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
