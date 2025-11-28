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
          <th>Tanggal</th>
          <th>Keterangan</th>
        </tr>
      </thead>
      <tbody>
        @forelse($history as $h)
          <tr>
            <td>{{ $loop->iteration + ($history->currentPage()-1)*$history->perPage() }}</td>
            <td>{!! highlight($h->user->pegawai->nip, request('search')) !!}</td>
            <td>{!! highlight($h->user->name, request('search')) !!}</td>
            <td>{{ $h->user->pegawai->bidang->nama_bidang ?? '-' }}</td>
            <td>
            {{ \Carbon\Carbon::parse($h->tanggal)->format('d/m/Y') }}
            <br>
            <small class="text-muted">
                {{ $h->jam_masuk ? \Carbon\Carbon::parse($h->jam_masuk)->format('H:i') : '-' }}
            </small>
            </td>
            <td>
              @if($h->status === 'telat')
                <span class="badge bg-warning">Telat {{ $h->telat_menit }} menit</span>
              @else
                <span class="badge bg-success">Sesuai Jadwal</span>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted">Belum ada data apel pagi</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $history->links('pagination::bootstrap-5') }}
  </div>
</div>
