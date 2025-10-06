@extends('layouts.admin')

@section('title','Riwayat Kunjungan')
@section('page-title','Riwayat Kunjungan')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Riwayat Kunjungan</h4>
    <span class="badge badge-primary">{{ $riwayat->count() }} Riwayat</span>
  </div>

  <div class="card-body">
    {{-- Tabs filter --}}
    <ul class="nav nav-pills mb-3" id="riwayat-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link {{ request('filter') == null ? 'active' : '' }}"
           href="{{ route('pegawai.kunjungan.riwayat') }}">
          Semua
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request('filter') == 'selesai' ? 'active' : '' }}"
           href="{{ route('pegawai.kunjungan.riwayat', ['filter' => 'selesai']) }}">
          Diterima & Selesai
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request('filter') == 'ditolak' ? 'active' : '' }}"
           href="{{ route('pegawai.kunjungan.riwayat', ['filter' => 'ditolak']) }}">
          Ditolak
        </a>
      </li>
    </ul>

    <div class="table-responsive">
      <table class="table table-striped table-bordered mb-0">
        <thead class="thead-dark">
          <tr>
            <th>Nama Tamu</th>
            <th>Email</th>
            <th>Keperluan</th>
            <th>Status</th>
            <th>Waktu Masuk</th>
            <th>Waktu Keluar</th>
          </tr>
        </thead>
        <tbody>
          @forelse($riwayat as $k)
            <tr>
              <td>{{ $k->tamu?->nama ?? $k->tamu?->user?->name ?? '-' }}</td>
              <td>{{ $k->tamu->email }}</td>
              <td>{{ $k->keperluan }}</td>
              <td>
                @if($k->status === 'selesai')
                  <span class="badge badge-success">Selesai</span>
                @elseif($k->status === 'ditolak')
                  <span class="badge badge-danger">Ditolak</span>
                @endif
              </td>
              <td>{{ $k->waktu_masuk }}</td>
              <td>
                @if($k->waktu_keluar)
                  <span class="text-success">{{ $k->waktu_keluar }}</span>
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-3">
                Belum ada riwayat kunjungan
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
