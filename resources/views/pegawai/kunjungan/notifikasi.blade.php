@extends('layouts.admin')

@section('title','Notifikasi Tamu')
@section('page-title','Notifikasi Tamu')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Tamu yang Sedang Datang</h4>
    <span class="badge badge-info">{{ $notifikasi->count() }} Tamu</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="thead-light">
          <tr>
            <th>Nama Tamu</th>
            <th>Email</th>
            <th>Keperluan</th>
            <th>Status</th>
            <th>Waktu Masuk</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($notifikasi as $k)
            <tr>
              <td><strong>{{ $k->tamu?->nama ?? $k->tamu?->user?->name ?? '-' }}</strong></td>
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
              <td>
                @if($k->status === 'menunggu')
                    <form action="{{ route('pegawai.kunjungan.konfirmasi', $k->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="aksi" value="terima">
                    <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Saya Bisa Menerima
                    </button>
                    </form>

                    <form action="{{ route('pegawai.kunjungan.konfirmasi', $k->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="aksi" value="tolak">
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-times"></i> Saya Tidak Bisa
                    </button>
                    </form>
                @elseif($k->status === 'sedang_bertamu')
                    <span class="badge badge-success">Diterima</span>
                @elseif($k->status === 'ditolak')
                    <span class="badge badge-danger">Ditolak</span>
                @endif
                </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center text-muted py-3">
                Tidak ada tamu saat ini
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
