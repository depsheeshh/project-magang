@extends('layouts.admin')

@section('title','Daftar Kunjungan')
@section('page-title','Daftar Kunjungan Tamu')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Daftar Kunjungan</h4>
  </div>
  <div class="card-body">

    {{-- Tab navigasi filter status --}}
    <ul class="nav nav-pills mb-3">
      <li class="nav-item">
        <a class="nav-link {{ !request()->has('status') ? 'active' : '' }}"
           href="{{ route('frontliner.kunjungan.index') }}">
          Semua
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request('status')==='menunggu' ? 'active' : '' }}"
           href="{{ route('frontliner.kunjungan.index',['status'=>'menunggu']) }}">
          Menunggu
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request('status')==='sedang_bertamu' ? 'active' : '' }}"
           href="{{ route('frontliner.kunjungan.index',['status'=>'sedang_bertamu']) }}">
          Sedang Bertamu
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request('status')==='selesai' ? 'active' : '' }}"
           href="{{ route('frontliner.kunjungan.index',['status'=>'selesai']) }}">
          Selesai
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request('status')==='ditolak' ? 'active' : '' }}"
           href="{{ route('frontliner.kunjungan.index',['status'=>'ditolak']) }}">
          Ditolak
        </a>
      </li>
    </ul>

    {{-- Tabel daftar kunjungan --}}
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Nama Tamu</th>
            <th>Bidang</th>
            <th>Pegawai Tujuan</th>
            <th>Keperluan</th>
            <th>Waktu Masuk</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kunjungan as $k)
            <tr>
              <td>{{ $k->tamu->nama ?? $k->tamu->user->name ?? '-' }}</td>
              <td>{{ $k->pegawai->bidang->nama_bidang ?? '-' }}</td>
              <td>{{ $k->pegawai->user->name ?? '-' }}</td>
              <td>{{ $k->keperluan }}</td>
              <td>{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('d/m/Y H:i') }}</td>
              <td>
                @if($k->status === 'menunggu')
                  <span class="badge badge-warning">Menunggu</span>
                @elseif($k->status === 'sedang_bertamu')
                  <span class="badge badge-info">Sedang Bertamu</span>
                @elseif($k->status === 'ditolak')
                  <span class="badge badge-danger">Ditolak</span>
                @elseif($k->status === 'selesai')
                  <span class="badge badge-success">Selesai</span>
                @endif
              </td>
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
                  <em>-</em>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center">Belum ada kunjungan</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
