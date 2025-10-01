@extends('layouts.admin')

@section('title','History Logs')
@section('page-title','History Logs')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Audit Trail / History Logs</h4>
  </div>
  <div class="card-body">
    <p class="mb-2">Semua aksi CRUD yang tercatat oleh observer akan muncul di sini.</p>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Aksi</th>
          <th>Tabel</th>
          <th>Record ID</th>
          <th>Reason</th>
          <th>Waktu</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
        <tr>
          <td>{{ $loop->iteration + ($logs->currentPage()-1)*$logs->perPage() }}</td>
          <td>{{ $log->user->name ?? 'System/Seeder' }}</td>
          <td><span class="badge badge-info">{{ strtoupper($log->action) }}</span></td>
          <td>{{ $log->table_name }}</td>
          <td>{{ $log->record_id }}</td>
          <td>{{ \Illuminate\Support\Str::limit($log->reason, 40) }}</td>
          <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
          <td>
            <a href="{{ route('admin.history_logs.show',$log->id) }}"
               class="btn btn-info btn-sm" title="Lihat Detail">
              <i class="fas fa-eye"></i>
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center">Belum ada log</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    {{ $logs->links() }}
  </div>
</div>
@endsection
