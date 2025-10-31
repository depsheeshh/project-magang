@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <h4 class="mb-3">📋 Detail Rapat: {{ $rapat->judul }}</h4>

  <div class="card mb-3">
    <div class="card-body">
      <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}</p>
      <p><strong>Lokasi:</strong> {{ $rapat->lokasi }}</p>
    </div>
  </div>

  <div class="card">
    <div class="card-header bg-dark text-white">
      Daftar Undangan
    </div>
    <div class="card-body table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Instansi</th>
            <th>Status Kehadiran</th>
            <th>Check-in At</th>
          </tr>
        </thead>
        <tbody>
          @foreach($rapat->undangan as $u)
          <tr>
            <td>{{ $u->user->name ?? '-' }}</td>
            <td>{{ $u->instansi->nama_instansi ?? '-' }}</td>
            <td>
              @if($u->status_kehadiran === 'hadir')
                <span class="badge bg-success">Hadir</span>
              @elseif($u->status_kehadiran === 'tidak_hadir')
                <span class="badge bg-danger">Tidak Hadir</span>
              @else
                <span class="badge bg-warning">Pending</span>
              @endif
            </td>
            <td>{{ optional($u->checked_in_at)->format('d/m H:i') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<a href="{{ route('frontliner.rapat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
