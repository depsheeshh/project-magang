@extends('layouts.admin')
@section('title','Kunjungan Tamu')
@section('page-title','Kunjungan Tamu')

@section('content')
<div class="card">
  <div class="card-header"><h5>Riwayat Kunjungan Tamu</h5></div>
  <div class="card-body table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Tamu</th>
          <th>Instansi</th>
          <th>Bidang Tujuan</th>
          <th>Pegawai Tujuan</th>
          <th>Keperluan</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        @foreach($kunjunganList as $k)
        <tr>
          <td>{{ $loop->iteration }}</td>
          {{-- akses lewat tamu->user --}}
          <td>{{ $k->tamu->user->name ?? '-' }}</td>
          <td>{{ $k->tamu->instansi ?? '-' }}</td>
          <td>{{ $k->bidang->nama_bidang ?? '-' }}</td>
          <td>{{ $k->pegawai->user->name ?? '-' }}</td>
          <td>{{ $k->keperluan }}</td>
          <td>{{ $k->waktu_masuk ? $k->waktu_masuk->format('d/m/Y H:i') : '-' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $kunjunganList->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
