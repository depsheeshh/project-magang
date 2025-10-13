@extends('layouts.admin')

@section('title','Detail History Log')
@section('page-title','Detail History Log')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Detail Log</h4>
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">User</dt>
      <dd class="col-sm-9">{{ $historyLog->user->name ?? 'System/Seeder' }}</dd>

      <dt class="col-sm-3">Aksi</dt>
      <dd class="col-sm-9"><span class="badge badge-info">{{ strtoupper($historyLog->action) }}</span></dd>

      <dt class="col-sm-3">Tabel</dt>
      <dd class="col-sm-9">{{ $historyLog->table_name }}</dd>

      <dt class="col-sm-3">Record ID</dt>
      <dd class="col-sm-9">{{ $historyLog->record_id }}</dd>

      <dt class="col-sm-3">Perubahan Data</dt>
      <dd class="col-sm-9">
        @php
            $oldValues = is_array($historyLog->old_values) ? $historyLog->old_values : (json_decode($historyLog->old_values, true) ?? []);
            $newValues = is_array($historyLog->new_values) ? $historyLog->new_values : (json_decode($historyLog->new_values, true) ?? []);
            $changes = [];
            $ignore = ['id','created_at','updated_at','deleted_at','deleted_id'];

            if ($historyLog->action === 'create') {
                foreach ($newValues as $key => $val) {
                    if (!in_array($key, $ignore)) {
                        $changes[$key] = ['old' => null, 'new' => $val];
                    }
                }
            } elseif ($historyLog->action === 'update') {
                foreach ($newValues as $key => $newVal) {
                    if (in_array($key, $ignore)) continue;
                    $oldVal = $oldValues[$key] ?? null;
                    if ($oldVal != $newVal) {
                        $changes[$key] = ['old' => $oldVal, 'new' => $newVal];
                    }
                }
            } elseif ($historyLog->action === 'delete') {
                $mainField = $oldValues['nama']
                    ?? $oldValues['nama_jabatan']
                    ?? $oldValues['nama_bidang']
                    ?? $historyLog->record_id;
                $changes = ['deleted' => ['old' => $mainField, 'new' => null]];
            }
            @endphp


        @if(count($changes))
          <ul class="list-unstyled">
            @foreach($changes as $field => $change)
              @if($field === 'deleted')
                <li><span class="text-danger">Data <strong>{{ $change['old'] }}</strong> dihapus</span></li>
              @elseif(is_null($change['old']))
                <li><strong>{{ ucfirst(str_replace('_',' ',$field)) }}</strong>:
                  <span class="text-success">{{ $change['new'] }}</span>
                </li>
              @else
                <li><strong>{{ ucfirst(str_replace('_',' ',$field)) }}</strong>:
                  <span class="text-danger">{{ $change['old'] }}</span>
                  <i class="fas fa-arrow-right mx-1"></i>
                  <span class="text-success">{{ $change['new'] }}</span>
                </li>
              @endif
            @endforeach
          </ul>
        @else
          <em>Tidak ada perubahan data</em>
        @endif
      </dd>

      <dt class="col-sm-3">Reason</dt>
      <dd class="col-sm-9">{{ $historyLog->reason ?? '-' }}</dd>

      <dt class="col-sm-3">Waktu</dt>
      <dd class="col-sm-9">{{ $historyLog->created_at->format('d-m-Y H:i:s') }}</dd>
    </dl>
    <a href="{{ route('admin.history_logs.index') }}" class="btn btn-secondary">Kembali</a>
  </div>
</div>
@endsection
