@extends('layouts.admin')

@section('title','Status Kunjungan')
@section('page-title','Status Kunjungan Saya')

@section('content')
<div class="card">
  <div class="card-header"><h4>Status Kunjungan</h4></div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Pegawai Tujuan</th>
          <th>Keperluan</th>
          <th>Status</th>
          <th>Alasan Penolakan</th>
          <th>Waktu Masuk</th>
          <th>Waktu Keluar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kunjungan as $k)
          <tr>
            <td>{{ $k->pegawai?->user?->name ?? '-' }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>
              @if($k->status === 'diterima')
                <span class="badge badge-success">Diterima</span>
              @elseif($k->status === 'ditolak')
                <span class="badge badge-danger">Ditolak</span>
              @elseif($k->status === 'sedang_bertamu')
                <span class="badge badge-warning">Sedang Bertamu</span>
              @else
                <span class="badge badge-secondary">{{ ucfirst($k->status) }}</span>
              @endif
            </td>
            <td>
              {{-- ✅ tampilkan alasan hanya jika ditolak --}}
              @if($k->status === 'ditolak')
                {{ $k->alasan_penolakan ?? '-' }}
              @else
                -
              @endif
            </td>
            <td>{{ $k->waktu_masuk }}</td>
            <td>{{ $k->waktu_keluar ?? '-' }}</td>
            <td>
                @if($k->status === 'sedang_bertamu')
                    <form action="{{ route('tamu.kunjungan.checkout',$k->id) }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Checkout
                    </button>
                    </form>
                @else
                    <span class="badge badge-secondary">{{ ucfirst($k->status) }}</span>
                @endif
                </td>

          </tr>
        @empty
          <tr><td colspan="5" class="text-center">Belum ada kunjungan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
