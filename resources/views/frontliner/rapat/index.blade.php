@extends('layouts.admin')

@section('content')
<div class="container-fluid">
  <h4 class="mb-3">📅 Rapat Hari Ini</h4>
  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>Judul</th>
            <th>Waktu</th>
            <th>Lokasi</th>
            <th>Total Undangan</th>
            <th>Hadir</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rapat as $r)
          <tr>
            <td>{{ $r->judul }}</td>
            <td>{{ \Carbon\Carbon::parse($r->waktu_mulai)->format('d/m H:i') }}</td>
            <td>{{ $r->lokasi }}</td>
            <td>{{ $r->undangan->count() }}</td>
            <td>
              <span class="badge bg-success">
                {{ $r->undangan->where('status_kehadiran','hadir')->count() }}
              </span>
            </td>
            <td>
              <a href="{{ route('frontliner.rapat.show',$r->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i> Detail
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center">Tidak ada rapat hari ini</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
