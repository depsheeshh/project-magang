@extends('layouts.admin')

@section('title','Laporan Kunjungan')
@section('page-title','Laporan Kunjungan')

@section('content')
<div class="card">
  <div class="card-header"><h4>Rekap Kunjungan</h4></div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Tamu</th>
          <th>Pegawai Tujuan</th>
          <th>Keperluan</th>
          <th>Status</th>
          <th>Waktu Masuk</th>
          <th>Waktu Keluar</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kunjungan as $k)
          <tr>
            <td>{{ $k->tamu?->nama ?? $k->tamu?->user?->name ?? '-' }}</td>
            <td>{{ $k->pegawai?->user?->name ?? '-' }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>{{ ucfirst($k->status) }}</td>
            <td>{{ $k->waktu_masuk }}</td>
            <td>{{ $k->waktu_keluar ?? '-' }}</td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center">Belum ada data kunjungan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
