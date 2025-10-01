@extends('layouts.admin')

@section('title','Daftar Tamu Menunggu')
@section('page-title','Daftar Tamu Menunggu')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Daftar Tamu Menunggu</h4>
  </div>
  <div class="card-body">
    @if(session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Tamu</th>
          <th>Email</th>
          <th>Pegawai Tujuan</th>
          <th>Keperluan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kunjunganMenunggu as $k)
          <tr>
            <td>{{ $k->tamu->nama }}</td>
            <td>{{ $k->tamu->email }}</td>
            <td>{{ $k->pegawai->user->name }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>
              <form action="{{ route('kunjungan.approve',$k) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-success btn-sm">Setujui</button>
              </form>
              <form action="{{ route('kunjungan.reject',$k) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-danger btn-sm">Tolak</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center">Tidak ada tamu menunggu</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
