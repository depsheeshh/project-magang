@extends('layouts.app')

@section('title','Status Kunjungan')
@section('content')
<div class="container mt-5 py-5">
  <h2 class="mt-5">Status Kunjungan Saya</h2>

  @if($kunjungan->isEmpty())
    <div class="alert alert-info mt-3">
      Anda belum memiliki riwayat kunjungan.
    </div>
  @else
    <table class="table table-bordered mt-3">
      <thead class="table-light">
        <tr>
          <th>Tanggal</th>
          <th>Keperluan</th>
          <th>Bidang</th>
          <th>Pegawai Tujuan</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($kunjungan as $k)
          <tr>
            <td>{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('d/m/Y H:i') }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>{{ $k->pegawai->bidang->nama_bidang ?? '-' }}</td>
            <td>{{ $k->pegawai->user->name ?? '-' }}</td>
            <td>
              @switch($k->status)
                @case('menunggu')
                  <span class="badge bg-warning text-dark">Menunggu</span>
                  @break
                @case('disetujui')
                  <span class="badge bg-success">Disetujui</span>
                  @break
                @case('ditolak')
                  <span class="badge bg-danger">Ditolak</span>
                  @break
                @case('sedang_bertamu')
                  <span class="badge bg-primary">Sedang Bertamu</span>
                  @break
                @case('selesai')
                  <span class="badge bg-secondary">Selesai</span>
                  @break
                @default
                  <span class="badge bg-light text-dark">{{ $k->status }}</span>
              @endswitch
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
