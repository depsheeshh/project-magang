@extends('layouts.admin')

@section('title','Riwayat Kunjungan')
@section('page-title','Riwayat Kunjungan')

@section('content')
<div class="card">
  <div class="card-header"><h4>Riwayat Kunjungan</h4></div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Tamu</th>
          <th>Email</th>
          <th>Keperluan</th>
          <th>Waktu Masuk</th>
          <th>Waktu Keluar</th>
        </tr>
      </thead>
      <tbody>
        @forelse($riwayat as $k)
          <tr>
            <td>{{ $k->tamu->name }}</td>
            <td>{{ $k->tamu->email }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>{{ $k->waktu_masuk }}</td>
            <td>{{ $k->waktu_keluar }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center">Belum ada riwayat kunjungan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
