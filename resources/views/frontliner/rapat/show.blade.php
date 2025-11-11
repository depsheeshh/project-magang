@extends('layouts.admin')

@section('title','Detail Rapat')

@section('content')
<div class="container-fluid">
  <h4 class="mb-3">📋 Detail Rapat: {{ $rapat->judul }}</h4>

  <div class="card mb-3 shadow-sm">
    <div class="card-body">
      <p><strong>Jenis Rapat:</strong> <span class="badge bg-info text-uppercase">{{ $rapat->jenis_rapat }}</span></p>
      <p><strong>Waktu:</strong>
        {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
        –
        {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
      </p>
      <p><strong>Lokasi:</strong> {{ $rapat->lokasi }}</p>
      <p><strong>Status:</strong>
        @if($rapat->status === 'belum_dimulai')
          <span class="badge bg-warning text-dark">Belum Dimulai</span>
        @elseif($rapat->status === 'berjalan')
          <span class="badge bg-primary">Sedang Berjalan</span>
        @elseif($rapat->status === 'selesai')
          <span class="badge bg-success">Selesai</span>
        @elseif($rapat->status === 'dibatalkan')
          <span class="badge bg-secondary">Dibatalkan</span>
        @endif
      </p>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <i class="fas fa-users"></i> Daftar Undangan
    </div>
    <div class="card-body table-responsive">

      {{-- ✅ Bedakan tabel berdasarkan jenis rapat --}}
      @if($rapat->jenis_rapat === 'Internal')
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Nama Pegawai</th>
              <th>Jabatan</th>
              <th>Status Kehadiran</th>
              <th>Check-in</th>
              <th>Check-out</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rapat->undangan as $u)
            <tr>
              <td>{{ $u->user->name ?? '-' }}</td>
              <td>{{ $u->jabatan ?? ($u->user->pegawai->jabatan ?? '-') }}</td>
              <td>
                @switch($u->status_kehadiran)
                  @case('pending') <span class="badge bg-warning text-dark">Belum Check-in</span> @break
                  @case('hadir')   <span class="badge bg-success">Hadir</span> @break
                  @case('selesai') <span class="badge bg-secondary">Selesai</span> @break
                  @default         <span class="badge bg-danger">Tidak Hadir</span>
                @endswitch
              </td>
              <td>{{ optional($u->checked_in_at)->format('d/m H:i') ?? '-' }}</td>
              <td>{{ optional($u->checked_out_at)->format('d/m H:i') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center text-muted">
                <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                Belum ada undangan untuk rapat ini
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      @else
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>Nama</th>
              <th>Instansi Asal</th>
              <th>Jabatan</th>
              <th>Email</th>
              <th>Status Kehadiran</th>
              <th>Check-in</th>
              <th>Check-out</th>
            </tr>
          </thead>
          <tbody>
            @forelse($rapat->undangan as $u)
            <tr>
              <td>{{ $u->nama ?? $u->user->name ?? '-' }}</td>
              <td>{{ $u->instansi->nama_instansi ?? ($u->user->instansi->nama_instansi ?? '-') }}</td>
              <td>{{ $u->jabatan ?? '-' }}</td>
              <td>{{ $u->email ?? $u->user->email ?? '-' }}</td>
              <td>
                @switch($u->status_kehadiran)
                  @case('pending') <span class="badge bg-warning text-dark">Belum Check-in</span> @break
                  @case('hadir')   <span class="badge bg-success">Hadir</span> @break
                  @case('selesai') <span class="badge bg-secondary">Selesai</span> @break
                  @default         <span class="badge bg-danger">Tidak Hadir</span>
                @endswitch
              </td>
              <td>{{ optional($u->checked_in_at)->format('d/m H:i') ?? '-' }}</td>
              <td>{{ optional($u->checked_out_at)->format('d/m H:i') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted">
                <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                Belum ada undangan untuk rapat ini
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      @endif

    </div>
  </div>
</div>

<a href="{{ route('frontliner.rapat.index') }}" class="btn btn-secondary mt-3">
  <i class="fas fa-arrow-left"></i> Kembali
</a>
@endsection
