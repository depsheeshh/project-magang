<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>No</th>
        <th>NIP</th>
        <th>Nama</th>
        <th>Bidang</th>
        <th>Jabatan</th>
        <th>QR Code</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pegawai as $p)
        <tr>
          <td>{{ $loop->iteration + ($pegawai->currentPage()-1)*$pegawai->perPage() }}</td>
          <td>{{ $p->nip }}</td>
          <td>{{ $p->user->name }}</td>
          <td>{{ $p->bidang->nama_bidang ?? '-' }}</td>
          <td>{{ $p->jabatan->nama_jabatan ?? '-' }}</td>
          <td class="py-3">
              @php
                  $sudahAbsen = \App\Models\ApelPagi::whereDate('tanggal', today())
                  ->where('user_id', $p->user_id)
                  ->exists();
              @endphp

              @if($sudahAbsen)
                  <span class="badge bg-success">
                  <i class="fa-solid fa-check-circle me-1"></i> Sudah Absen
                  </span>
                  <small class="text-muted d-block">QR disembunyikan</small>
              @else
                  {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(route('apelpagi.show',$p->nip)) !!}
              @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="text-center text-muted">Tidak ada pegawai ditemukan</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- Pagination --}}
<div class="mt-3">
  {{ $pegawai->links('pagination::bootstrap-5') }}
</div>
