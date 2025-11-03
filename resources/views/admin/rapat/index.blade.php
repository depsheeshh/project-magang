@extends('layouts.admin')

@section('title','Data Rapat')
@section('page-title','Data Rapat')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0"><i class="fas fa-handshake"></i> Manajemen Rapat</h4>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createRapatModal">
      <i class="fas fa-plus-circle"></i> Tambah Rapat
    </button>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle">
        <thead class="thead-dark text-center">
          <tr>
            <th>#</th>
            <th>Judul</th>
            <th>Waktu</th>
            <th>Jenis Rapat</th>
            <th>Lokasi</th>
            <th>Ruangan</th>
            <th>Radius</th>
            <th>Jumlah Tamu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rapat as $r)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>
              <strong>{{ $r->judul }}</strong><br>
              @if($r->status === 'selesai')
                <span class="badge badge-success">Selesai</span>
              @elseif($r->status === 'berjalan')
                <span class="badge badge-primary">Sedang Berjalan</span>
              @elseif($r->status === 'dibatalkan')
                <span class="badge badge-secondary">Dibatalkan</span>
              @endif
            </td>
            <td>
              {{ \Carbon\Carbon::parse($r->waktu_mulai)->format('d/m/Y H:i') }} -
              {{ \Carbon\Carbon::parse($r->waktu_selesai)->format('d/m/Y H:i') }}
            </td>
            <td class="text-center">
              <span class="badge badge-info text-uppercase">{{ $r->jenis_rapat }}</span>
            </td>
            <td>{{ $r->lokasi }}</td>
            <td>{{ $r->ruangan->nama_ruangan ?? '-' }}</td>
            <td class="text-center"><span class="badge badge-warning">{{ $r->radius }} m</span></td>
            <td class="text-center"><span class="badge badge-success">{{ $r->jumlah_tamu ?? 0 }}</span></td>
            <td class="text-center">
              <a href="{{ route('admin.rapat.show',$r->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
              <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editRapatModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
              <form action="{{ route('admin.rapat.destroy',$r->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus rapat ini?')"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="9" class="text-center text-muted">Belum ada rapat</td></tr>
          @endforelse
        </tbody>
      </table>
      {{ $rapat->links() }}
    </div>
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Create -->
<div class="modal fade" id="createRapatModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.rapat.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Tambah Rapat</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" placeholder="Masukkan judul rapat" required>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Waktu Mulai</label>
              <input type="datetime-local" name="waktu_mulai"
                     class="form-control"
                     min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="form-group col-md-6">
              <label>Waktu Selesai</label>
              <input type="datetime-local" name="waktu_selesai"
                     class="form-control"
                     min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
          </div>

          <div class="form-group">
            <label>Jenis Rapat</label>
            <select name="jenis_rapat" class="form-control" required>
              <option value="Internal">Rapat Internal</option>
              <option value="Eksternal">Rapat Eksternal</option>
            </select>
          </div>

          <div class="form-group">
            <label>Lokasi (Kantor)</label>
            <select name="lokasi" id="lokasiSelectCreate" class="form-control" required>
              <option value="">-- Pilih Kantor --</option>
              @foreach($kantor as $k)
                <option value="{{ $k->nama_kantor }}"
                        data-id="{{ $k->id }}"
                        data-lat="{{ $k->latitude }}"
                        data-lon="{{ $k->longitude }}">
                  {{ $k->nama_kantor }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Ruangan</label>
            <select name="ruangan_id" id="ruanganSelectCreate" class="form-control" required>
              <option value="">-- Pilih Ruangan --</option>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Latitude</label>
              <input type="text" name="latitude" id="latitudeCreate" class="form-control" readonly>
            </div>
            <div class="form-group col-md-6">
              <label>Longitude</label>
              <input type="text" name="longitude" id="longitudeCreate" class="form-control" readonly>
            </div>
          </div>

          <div class="form-group">
            <label>Radius (meter)</label>
            <input type="number" name="radius" id="radiusCreate" value="100" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Jumlah Tamu</label>
            <input type="number" name="jumlah_tamu" class="form-control" placeholder="Maksimal tamu">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
@foreach($rapat as $r)
<div class="modal fade" id="editRapatModal{{ $r->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('admin.rapat.update',$r->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title">Edit Rapat</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" value="{{ $r->judul }}" class="form-control" required>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Waktu Mulai</label>
              <input type="datetime-local" name="waktu_mulai"
                     value="{{ \Carbon\Carbon::parse($r->waktu_mulai)->format('Y-m-d\TH:i') }}"
                     class="form-control"
                     min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
            <div class="form-group col-md-6">
              <label>Waktu Selesai</label>
              <input type="datetime-local" name="waktu_selesai"
                     value="{{ \Carbon\Carbon::parse($r->waktu_selesai)->format('Y-m-d\TH:i') }}"
                     class="form-control"
                     min="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>
          </div>

          <div class="form-group">
            <label>Jenis Rapat</label>
            <select name="jenis_rapat" class="form-control" required>
              <option value="Internal" {{ $r->jenis_rapat == 'Internal' ? 'selected' : '' }}>Rapat Internal</option>
              <option value="Eksternal" {{ $r->jenis_rapat == 'Eksternal' ? 'selected' : '' }}>Rapat Eksternal</option>
            </select>
          </div>

          <div class="form-group">
            <label>Lokasi (Kantor)</label>
            <select name="lokasi" id="lokasiSelect{{ $r->id }}" class="form-control" required>
              <option value="">-- Pilih Kantor --</option>
              @foreach($kantor as $k)
                <option value="{{ $k->nama_kantor }}"
                        data-id="{{ $k->id }}"
                        data-lat="{{ $k->latitude }}"
                        data-lon="{{ $k->longitude }}"
                        {{ $r->lokasi == $k->nama_kantor ? 'selected' : '' }}>
                  {{ $k->nama_kantor }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Ruangan</label>
            <select name="ruangan_id" id="ruanganSelect{{ $r->id }}" class="form-control" data-selected="{{ $r->ruangan_id }}">
              <option value="">-- Pilih Ruangan --</option>
              {{-- opsi ruangan akan diisi via JS --}}
            </select>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Latitude</label>
              <input type="text" name="latitude" id="latitude{{ $r->id }}"
                     value="{{ $r->latitude }}" class="form-control" readonly>
            </div>
            <div class="form-group col-md-6">
              <label>Longitude</label>
              <input type="text" name="longitude" id="longitude{{ $r->id }}"
                     value="{{ $r->longitude }}" class="form-control" readonly>
            </div>
          </div>

          <div class="form-group">
            <label>Radius (meter)</label>
            <input type="number" name="radius" id="radius{{ $r->id }}"
                   value="{{ $r->radius ?? 100 }}" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Jumlah Tamu</label>
            <input type="number" name="jumlah_tamu" value="{{ $r->jumlah_tamu }}" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
  function bindLokasi(selectId, latId, lonId, ruanganSelectId, ruanganData) {
    const select = document.getElementById(selectId);
    const latInput = document.getElementById(latId);
    const lonInput = document.getElementById(lonId);
    const ruanganSelect = document.getElementById(ruanganSelectId);

    if (!select) return;

    select.addEventListener('change', function() {
      const option = this.options[this.selectedIndex];
      latInput.value = option.getAttribute('data-lat') || '';
      lonInput.value = option.getAttribute('data-lon') || '';

      // reset dropdown ruangan
      if (ruanganSelect) {
        ruanganSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
        const kantorId = option.getAttribute('data-id');
        if (kantorId && ruanganData[kantorId]) {
          ruanganData[kantorId].forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.nama_ruangan;
            ruanganSelect.appendChild(opt);
          });
        }
      }
    });

    // trigger sekali kalau ada value awal
    if (select.value) {
      const option = select.options[select.selectedIndex];
      latInput.value = option.getAttribute('data-lat') || '';
      lonInput.value = option.getAttribute('data-lon') || '';

      if (ruanganSelect) {
        ruanganSelect.innerHTML = '<option value="">-- Pilih Ruangan --</option>';
        const kantorId = option.getAttribute('data-id');
        if (kantorId && ruanganData[kantorId]) {
          ruanganData[kantorId].forEach(r => {
            const opt = document.createElement('option');
            opt.value = r.id;
            opt.textContent = r.nama_ruangan;
            // auto select jika ruangan sudah tersimpan
            if (ruanganSelect.getAttribute('data-selected') == r.id) {
              opt.selected = true;
            }
            ruanganSelect.appendChild(opt);
          });
        }
      }
    }
  }

  // Data ruangan dari backend
  const ruanganData = @json(
    $kantor->mapWithKeys(fn($k) => [
      $k->id => $k->ruangan->map(fn($r) => [
        'id' => $r->id,
        'nama_ruangan' => $r->nama_ruangan
      ])
    ])
  );

  // untuk create
  bindLokasi('lokasiSelectCreate', 'latitudeCreate', 'longitudeCreate', 'ruanganSelectCreate', ruanganData);

  // untuk edit (loop semua rapat)
  @foreach($rapat as $r)
    bindLokasi(
      'lokasiSelect{{ $r->id }}',
      'latitude{{ $r->id }}',
      'longitude{{ $r->id }}',
      'ruanganSelect{{ $r->id }}',
      ruanganData
    );
  @endforeach
});
</script>
@endpush

