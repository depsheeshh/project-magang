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
        @forelse($kunjungan as $k)
          <tr>
            <td>{{ $k->tamu->nama }}</td>
            <td>{{ $k->tamu->email }}</td>
            <td>{{ $k->pegawai->user->name }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>
                @if($k->status === 'menunggu')
                    <form action="{{ route('frontliner.kunjungan.approve',$k->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn btn-sm btn-success">Setujui</button>
                    </form>
                    <form action="{{ route('frontliner.kunjungan.reject',$k->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn btn-sm btn-danger">Tolak</button>
                    </form>
                @elseif($k->status === 'sedang_bertamu')
                    <form action="{{ route('frontliner.kunjungan.checkout',$k->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button class="btn btn-sm btn-primary">Checkout</button>
                    </form>
                @else
                    <span class="badge badge-secondary">{{ ucfirst($k->status) }}</span>
                @endif
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
