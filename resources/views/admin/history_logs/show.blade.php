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

      <dt class="col-sm-3">Old Values</dt>
      <dd class="col-sm-9">
        @if($historyLog->old_values)
          <pre>{{ json_encode(json_decode($historyLog->old_values), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
        @else
          <em>-</em>
        @endif
      </dd>

      <dt class="col-sm-3">New Values</dt>
      <dd class="col-sm-9">
        @if($historyLog->new_values)
          <pre>{{ json_encode(json_decode($historyLog->new_values), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
        @else
          <em>-</em>
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
