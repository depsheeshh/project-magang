@php
function highlight($text, $search) {
    if (!$search) return $text;
    return preg_replace("/(" . preg_quote($search, '/') . ")/i",
        '<mark>$1</mark>', $text);
}
@endphp

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
          <td>{!! highlight($p->nip, request('search')) !!}</td>
          <td>{!! highlight($p->user->name, request('search')) !!}</td>
          <td>{!! highlight($p->bidang->nama_bidang ?? '-', request('search')) !!}</td>
          <td>{!! highlight($p->jabatan->nama_jabatan ?? '-', request('search')) !!}</td>
          <td class="py-3">
            @php
                $absen = \App\Models\ApelPagi::whereDate('tanggal', today())
                    ->where('user_id', $p->user_id)
                    ->first();
            @endphp

            @if($absen)
                <span class="badge bg-success">
                <i class="fa-solid fa-check-circle me-1"></i> Sudah Absen
                </span>
                <small class="text-muted d-block">
                Sudah Discan pada
                {{ \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i:s') }} –
                {{ \Carbon\Carbon::parse($absen->jam_masuk)->translatedFormat('l') }}
                </small>
            @else
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(120)->generate(route('apelpagi.show',$p->apel_token)) !!}
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
