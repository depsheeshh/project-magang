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
    <table class="table table-striped table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>User</th>
          <th>Aksi</th>
          <th>Tabel</th>
          <th>Record ID</th>
          <th>Ringkasan</th>
          <th>Waktu</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
        @php
          $oldValues = is_array($log->old_values) ? $log->old_values : (json_decode($log->old_values, true) ?? []);
          $newValues = is_array($log->new_values) ? $log->new_values : (json_decode($log->new_values, true) ?? []);
          $ignore = ['id','created_at','updated_at','deleted_at','deleted_id','created_id','updated_id'];

          $changes = [];
          if (in_array($log->action, ['updated','update'])) {
              foreach ($newValues as $key => $val) {
                  if (!in_array($key, $ignore)) {
                      $oldVal = $oldValues[$key] ?? null;
                      if ($oldVal != $val) {
                          $changes[$key] = ['old'=>$oldVal,'new'=>$val];
                      }
                  }
              }
          } elseif (in_array($log->action, ['created','create'])) {
              foreach ($newValues as $key => $val) {
                  if (!in_array($key, $ignore)) {
                      $changes[$key] = ['old'=>null,'new'=>$val];
                  }
              }
          } elseif (in_array($log->action, ['deleted','delete'])) {
              $changes = ['deleted'=>['old'=>$oldValues,'new'=>null]];
          }
          $changeCount = count($changes);
        @endphp
        <tr>
          <td>{{ $loop->iteration + ($logs->currentPage()-1)*$logs->perPage() }}</td>
          <td>{{ $log->user->name ?? 'System/Seeder' }}</td>
          <td>
            @if(in_array($log->action,['created','create']))
              <span class="badge badge-success" title="Data ditambahkan">{{ strtoupper($log->action) }}</span>
            @elseif(in_array($log->action,['updated','update']))
              <span class="badge badge-warning" title="Data diubah">{{ strtoupper($log->action) }}</span>
            @elseif(in_array($log->action,['deleted','delete']))
              <span class="badge badge-danger" title="Data dihapus">{{ strtoupper($log->action) }}</span>
            @else
              <span class="badge badge-info">{{ strtoupper($log->action) }}</span>
            @endif
          </td>
          <td>{{ $log->table_name }}</td>
          <td>{{ $log->record_id }}</td>
          <td>
            @if($changeCount > 0)
              {{ $changeCount }} perubahan field
            @else
              {{ \Illuminate\Support\Str::limit($log->reason, 40) }}
            @endif
          </td>
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
    {{ $logs->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
