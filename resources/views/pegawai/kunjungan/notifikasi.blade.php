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
                @elseif($k->status == 'ditolak')
                  <span class="badge badge-danger">Ditolak</span>
                @endif
              </td>
              <td>{{ $k->waktu_masuk ?? '-' }}</td>
              <td>
                @if($k->status === 'menunggu')
                  {{-- Terima --}}
                  <form action="{{ route('pegawai.kunjungan.konfirmasi', $k->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="aksi" value="terima">
                    <button type="submit" class="btn btn-sm btn-success">
                      <i class="fas fa-check"></i> Saya Bisa Menerima
                    </button>
                  </form>

                  {{-- Tolak (pakai modal alasan) --}}
                  <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#tolakModal{{ $k->id }}">
                    <i class="fas fa-times"></i> Saya Tidak Bisa
                  </button>
                @elseif($k->status === 'sedang_bertamu')
                  <span class="badge badge-success">Diterima</span>
                @elseif($k->status === 'ditolak')
                  <span class="badge badge-danger">Ditolak</span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-3">
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

@section('modals')
  @foreach($notifikasi as $k)
    @if($k->status === 'menunggu')
    <!-- Modal Tolak -->
    <div class="modal fade" id="tolakModal{{ $k->id }}" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form action="{{ route('pegawai.kunjungan.konfirmasi', $k->id) }}" method="POST">
            @csrf
            <input type="hidden" name="aksi" value="tolak">
            <div class="modal-header">
              <h5 class="modal-title">Alasan Penolakan</h5>
              <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Alasan</label>
                <textarea name="reason" class="form-control" required placeholder="Tuliskan alasan menolak tamu ini..."></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger">Tolak</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    @endif
  @endforeach
@endsection
