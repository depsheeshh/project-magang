@extends('layouts.admin')

@section('title','Rapat Hari Ini')

@section('content')
<div class="container-fluid">
  <h4 class="mb-3">📅 Rapat Hari Ini</h4>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark text-center">
          <tr>
            <th>Judul</th>
            <th>Jenis</th>
            <th>Waktu</th>
            <th>Lokasi</th>
            <th>Status</th>
            <th>Total Undangan</th>
            <th>Hadir</th>
            <th>Tidak Hadir</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rapat as $r)
          <tr>
            <td><strong>{{ $r->judul }}</strong></td>
            <td><span class="badge bg-info text-uppercase">{{ $r->jenis_rapat }}</span></td>
            <td>
              {{ \Carbon\Carbon::parse($r->waktu_mulai)->format('d/m/Y H:i') }}
              –
              {{ \Carbon\Carbon::parse($r->waktu_selesai)->format('H:i') }}
            </td>
            <td>{{ $r->lokasi }}</td>
            <td>
              @if($r->status === 'belum_dimulai')
                <span class="badge bg-warning text-dark">Belum Dimulai</span>
              @elseif($r->status === 'berjalan')
                <span class="badge bg-primary">Sedang Berjalan</span>
              @elseif($r->status === 'selesai')
                <span class="badge bg-success">Selesai</span>
              @elseif($r->status === 'dibatalkan')
                <span class="badge bg-secondary">Dibatalkan</span>
              @endif
            </td>
            <td class="text-center">{{ $r->undangan->count() }}</td>
            <td class="text-center">
              <span class="badge bg-success">
                {{ $r->undangan->where('status_kehadiran','hadir')->count() }}
              </span>
            </td>
            <td class="text-center">
              <span class="badge bg-danger">
                {{ $r->undangan->where('status_kehadiran','tidak_hadir')->count() }}
              </span>
            </td>
            <td class="text-center">
              <a href="{{ route('frontliner.rapat.show',$r->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i> Detail
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center text-muted">
              <i class="fas fa-calendar-times fa-2x mb-2"></i><br>
              Tidak ada rapat hari ini
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
