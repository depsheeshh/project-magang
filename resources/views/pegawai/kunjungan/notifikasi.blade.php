@extends('layouts.admin')

@section('title','Notifikasi Tamu')
@section('page-title','Notifikasi Tamu')

@section('content')
<div class="card">
  <div class="card-header"><h4>Tamu yang Sedang Datang</h4></div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Tamu</th>
          <th>Email</th>
          <th>Keperluan</th>
          <th>Status</th>
          <th>Waktu Masuk</th>
        </tr>
      </thead>
      <tbody>
        @forelse($notifikasi as $k)
          <tr>
            <td>{{ $k->tamu->name }}</td>
            <td>{{ $k->tamu->email }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>
              @if($k->status == 'menunggu')
                <span class="badge badge-warning">Menunggu</span>
              @elseif($k->status == 'sedang_bertamu')
                <span class="badge badge-success">Sedang Bertamu</span>
              @endif
            </td>
            <td>{{ $k->waktu_masuk ?? '-' }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center">Tidak ada tamu saat ini</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
