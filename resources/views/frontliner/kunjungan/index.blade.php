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
      <li class="nav-item"><a class="nav-link {{ !request()->has('status') ? 'active' : '' }}" href="{{ route('frontliner.kunjungan.index') }}">Semua</a></li>
      <li class="nav-item"><a class="nav-link {{ request('status')==='menunggu' ? 'active' : '' }}" href="{{ route('frontliner.kunjungan.index',['status'=>'menunggu']) }}">Menunggu</a></li>
      <li class="nav-item"><a class="nav-link {{ request('status')==='sedang_bertamu' ? 'active' : '' }}" href="{{ route('frontliner.kunjungan.index',['status'=>'sedang_bertamu']) }}">Sedang Bertamu</a></li>
      <li class="nav-item"><a class="nav-link {{ request('status')==='selesai' ? 'active' : '' }}" href="{{ route('frontliner.kunjungan.index',['status'=>'selesai']) }}">Selesai</a></li>
      <li class="nav-item"><a class="nav-link {{ request('status')==='ditolak' ? 'active' : '' }}" href="{{ route('frontliner.kunjungan.index',['status'=>'ditolak']) }}">Ditolak</a></li>
    </ul>

    {{-- Tabel daftar kunjungan --}}
    <div class="table-responsive">
  <table class="table table-bordered table-striped align-middle shadow-sm">
    <thead class="thead-dark text-center">
      <tr>
        <th>Nama Tamu</th>
        <th>Bidang</th>
        <th>Pegawai Tujuan</th>
        <th>Keperluan</th>
        <th>Waktu Masuk</th>
        <th>Waktu Keluar</th>
        <th>Status</th>
        <th>Alasan Penolakan</th>
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
            @if($k->waktu_keluar)
              <span class="text-success">{{ \Carbon\Carbon::parse($k->waktu_keluar)->translatedFormat('d/m/Y H:i') }}</span>
            @else
              <span class="text-muted">-</span>
            @endif
          </td>

          <td>
            @if($k->status === 'menunggu')
              <span class="badge bg-warning text-dark">Menunggu</span>
            @elseif($k->status === 'sedang_bertamu')
              <span class="badge bg-info text-dark">Sedang Bertamu</span>
            @elseif($k->status === 'ditolak')
              <span class="badge bg-danger">Ditolak</span>
            @elseif($k->status === 'selesai')
              <span class="badge bg-success">Selesai</span>
            @endif
          </td>

          <td>
            @if($k->status === 'ditolak')
              {{ $k->alasan_penolakan ?? '-' }}
            @else
              <em>-</em>
            @endif
          </td>

          <td>
            @if($k->status === 'menunggu')
              <form action="{{ route('frontliner.kunjungan.approve',$k->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-success shadow-sm">
                  <i class="fas fa-check"></i> Setujui
                </button>
              </form>

              <button class="btn btn-sm btn-danger shadow-sm" data-toggle="modal" data-target="#tolakModal{{ $k->id }}">
                <i class="fas fa-times"></i> Tolak
              </button>
            @elseif($k->status === 'sedang_bertamu')
              <form action="{{ route('frontliner.kunjungan.checkout',$k->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-primary shadow-sm">
                  <i class="fas fa-door-open"></i> Checkout
                </button>
              </form>
            @else
              <em class="text-muted">-</em>
            @endif
          </td>
        </tr>

      @empty
        <tr>
          <td colspan="9" class="text-center py-5">
            <div class="d-flex flex-column align-items-center text-muted">
              <i class="fas fa-user-clock fa-3x mb-3 text-secondary"></i>
              <h6 class="mb-1 text-light">Belum ada kunjungan</h6>
              <small class="text-secondary">Data akan muncul setelah ada tamu yang melakukan kunjungan.</small>
            </div>
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
  @foreach($kunjungan as $k)
    @if($k->status === 'menunggu')
    <!-- Modal Tolak -->
    <div class="modal fade" id="tolakModal{{ $k->id }}" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form action="{{ route('frontliner.kunjungan.reject',$k->id) }}" method="POST">
            @csrf
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
   @if(session('survey_link'))
  <!-- Modal Link Survey -->
  <div class="modal fade show" id="surveyLinkModal" tabindex="-1" role="dialog" style="display:block;" aria-modal="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Link Survey Tamu</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Berikan link berikut kepada tamu untuk mengisi survey:</p>
          <div class="input-group">
            <input type="text" class="form-control" value="{{ session('survey_link') }}" id="surveyLinkInput" readonly>
            <button class="btn btn-outline-primary" type="button" onclick="copySurveyLink()">Copy</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

@endsection

@push('scripts')
@if(session('survey_link'))
<script>
  $(document).ready(function(){
    $('#surveyLinkModal').modal('show');
  });

  function copySurveyLink() {
    const input = document.getElementById('surveyLinkInput');
    if (!input) {
        toastr.error("Input link tidak ditemukan!");
        return;
    }

    // Pilih teks
    input.select();
    input.setSelectionRange(0, 99999); // untuk mobile

    // Gunakan Clipboard API jika tersedia
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(input.value).then(() => {
            toastr.success("Link survey berhasil disalin!");
        }).catch(() => {
            toastr.error("Gagal menyalin link.");
        });
    } else {
        // fallback lama
        document.execCommand("copy");
        toastr.success("Link survey berhasil disalin!");
    }
}
</script>
@endif
@endpush
