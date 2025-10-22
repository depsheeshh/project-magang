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
            <th>Lokasi</th>
            <th>Radius</th>
            <th>Jumlah Tamu</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rapat as $r)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td><strong>{{ $r->judul }}</strong></td>
            <td>
              {{ \Carbon\Carbon::parse($r->waktu_mulai)->format('d/m/Y H:i') }} -
              {{ \Carbon\Carbon::parse($r->waktu_selesai)->format('d/m/Y H:i') }}
            </td>
            <td>{{ $r->lokasi ?? '-' }}</td>
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
          <tr><td colspan="7" class="text-center text-muted">Belum ada rapat</td></tr>
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
              <input type="datetime-local" name="waktu_mulai" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
              <label>Waktu Selesai</label>
              <input type="datetime-local" name="waktu_selesai" class="form-control" required>
            </div>
          </div>

          @include('components.map-picker', [
            'mapId' => '', // kosong untuk create
            'lokasi' => old('lokasi'),
            'latitude' => old('latitude'),
            'longitude' => old('longitude'),
            'radius' => old('radius', 100),
            ])


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

<!-- Modal Edit Rapat -->
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
                     class="form-control" required>
            </div>
            <div class="form-group col-md-6">
              <label>Waktu Selesai</label>
              <input type="datetime-local" name="waktu_selesai"
                     value="{{ \Carbon\Carbon::parse($r->waktu_selesai)->format('Y-m-d\TH:i') }}"
                     class="form-control" required>
            </div>
          </div>

          @include('components.map-picker', [
            'mapId' => $r->id, // unik per rapat
            'lokasi' => $r->lokasi,
            'latitude' => $r->latitude,
            'longitude' => $r->longitude,
            'radius' => $r->radius,
            ])




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
<!-- Leaflet CSS & JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

  function initMap(mapId, latInputId, lonInputId, radiusInputId, defaultLat = -6.9175, defaultLon = 107.6191) {
    var latInput = document.getElementById(latInputId);
    var lonInput = document.getElementById(lonInputId);
    var radiusInput = document.getElementById(radiusInputId);

    var lat = parseFloat(latInput?.value) || defaultLat;
    var lon = parseFloat(lonInput?.value) || defaultLon;
    var zoom = (latInput?.value && lonInput?.value) ? 16 : 13;

    var map = L.map(mapId).setView([lat, lon], zoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var marker, circle;

    // Jika sudah ada koordinat lama → tampilkan marker & circle
    if (latInput?.value && lonInput?.value) {
      marker = L.marker([lat, lon]).addTo(map);
      circle = L.circle([lat, lon], { radius: parseInt(radiusInput.value) || 100, color: 'blue', fillOpacity: 0.2 }).addTo(map);
    }

    // Event klik peta
    map.on('click', function(e) {
      if (marker) map.removeLayer(marker);
      if (circle) map.removeLayer(circle);

      marker = L.marker(e.latlng).addTo(map);
      circle = L.circle(e.latlng, { radius: parseInt(radiusInput.value) || 100, color: 'blue', fillOpacity: 0.2 }).addTo(map);

      latInput.value = e.latlng.lat.toFixed(6);
      lonInput.value = e.latlng.lng.toFixed(6);
    });

    // Event ubah radius slider
    radiusInput.addEventListener('input', function() {
      if (circle && marker) {
        circle.setRadius(parseInt(this.value));
      }
    });
  }

  // Auto inisialisasi semua map yang ada
  document.querySelectorAll("[id^=map]").forEach(function(div) {
    var mapId = div.id;
    var suffix = mapId.replace("map", "");
    initMap(mapId, "latitude"+suffix, "longitude"+suffix, "radius"+suffix);
  });

});
</script>
@endpush

