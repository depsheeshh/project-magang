@extends('layouts.admin')

@section('title', 'Detail Role')
@section('page-title', 'Detail Role')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h4>Detail Role</h4>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th>Nama Role</th>
            <td>{{ ucfirst($role->name) }}</td>
          </tr>
          <tr>
            <th>Permissions</th>
            <td>
              @if($role->permissions->isNotEmpty())
                @foreach($role->permissions as $permission)
                  <span class="badge badge-primary">{{ $permission->name }}</span>
                @endforeach
              @else
                <span class="text-muted">Tidak ada permission</span>
              @endif
            </td>
          </tr>
        </table>
      </div>
      <div class="card-footer text-right">
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
</div>
@endsection
