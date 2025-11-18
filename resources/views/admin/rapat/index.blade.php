@php
  $prefix = Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
@endphp


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
                <th>Info Peserta</th> {{-- kolom generik --}}
                <th>Aksi</th>
            </tr>
            </thead>
        <tbody>
            @forelse($rapat as $r)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>
                <strong>{{ $r->judul }}</strong><br>
                @if($r->status === 'belum_dimulai')
                <span class="badge badge-warning">Belum Dimulai</span>
                @elseif($r->status === 'berjalan')
                <span class="badge badge-primary">Sedang Berjalan</span>
                @elseif($r->status === 'selesai')
                <span class="badge badge-success">Selesai</span>
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

                {{-- Kolom Info Peserta --}}
                <td class="text-center">
                @if($r->jenis_rapat === 'Internal')
                    @php
                        $hadir = $r->undangan->where('status_kehadiran','hadir')->count();
                        $total = $r->jumlah_tamu ?? 0;
                    @endphp
                    <span class="badge badge-success">Tamu Maks: {{ $total }}</span><br>
                    <span class="badge badge-primary">Hadir: {{ $hadir }}/{{ $total }}</span>
                @else
                    @php
                        $jumlahInstansi = $r->undanganInstansi->count();
                        $totalKuota     = $r->undanganInstansi->sum('kuota');
                        $totalHadir     = $r->undanganInstansi->sum('jumlah_hadir');
                    @endphp
                    <span class="badge badge-info">Instansi: {{ $jumlahInstansi }}/{{ $r->jumlah_instansi ?? '-' }}</span><br>
                    <span class="badge badge-success">Kuota Tamu: {{ $totalKuota }}/{{ $r->jumlah_tamu ?? '-' }}</span><br>
                    <span class="badge badge-primary">Hadir: {{ $totalHadir }}/{{ $totalKuota }}</span>
                @endif
                </td>

                <td class="text-center">
                <a href="{{ route($prefix.'.rapat.show', $r->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editRapatModal{{ $r->id }}"><i class="fas fa-edit"></i></button>
                <form action="{{ route($prefix.'.rapat.destroy', $r->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus rapat ini?')"><i class="fas fa-trash"></i></button>
                </form>
                <!-- Tombol baru Checkin Manual -->
                <a href="{{ route($prefix.'.rapat.peserta.index', $r->id) }}" class="btn btn-secondary btn-sm" title="Checkin Manual">
                    <i class="fas fa-users"></i>
                </a>
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
      <form action="{{ route($prefix.'.rapat.store') }}" method="POST">
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
            <select name="jenis_rapat" id="jenisRapatCreate" class="form-control" required>
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
            <label>Jumlah Tamu (Maksimal)</label>
            <input type="number" name="jumlah_tamu" class="form-control" placeholder="Maksimal tamu" min="1">
            @error('jumlah_tamu')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group" id="jumlahInstansiWrapperCreate" style="display:none;">
            <label>Jumlah Instansi (Maksimal)</label>
            <input type="number" name="jumlah_instansi" class="form-control" placeholder="Maksimal instansi" min="1">
            @error('jumlah_instansi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Survey Rapat</label>
            <select name="survey_id" id="surveySelectCreate" class="form-control">
                <option value="">-- Pilih Survey --</option>
                {{-- opsi diisi via AJAX --}}
            </select>
            @error('survey_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-check mt-2">
            <input type="checkbox" name="buat_survey_baru" class="form-check-input" id="buatSurveyBaruCreate">
            <label class="form-check-label" for="buatSurveyBaruCreate">
                Buat Survey Rapat Baru
            </label>
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
      <form action="{{ route($prefix.'.rapat.update', $r->id) }}" method="POST">
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
            <select name="jenis_rapat" id="jenisRapat{{ $r->id }}" class="form-control" required>
                <option value="internal" {{ $r->jenis_rapat == 'internal' ? 'selected' : '' }}>Rapat Internal</option>
                <option value="eksternal" {{ $r->jenis_rapat == 'eksternal' ? 'selected' : '' }}>Rapat Eksternal</option>
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
            <label>Jumlah Tamu (Maksimal)</label>
            <input type="number" name="jumlah_tamu" value="{{ $r->jumlah_tamu }}" class="form-control" min="1">
            @error('jumlah_tamu')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group" id="jumlahInstansiWrapper{{ $r->id }}" style="{{ $r->jenis_rapat == 'Eksternal' ? '' : 'display:none;' }}">
            <label>Jumlah Instansi (Maksimal)</label>
            <input type="number" name="jumlah_instansi" value="{{ $r->jumlah_instansi }}" class="form-control" min="1">
            @error('jumlah_instansi')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Survey Rapat</label>
            <select name="survey_id" id="surveySelect{{ $r->id }}" class="form-control">
                <option value="">-- Pilih Survey --</option>
                {{-- opsi diisi via AJAX --}}
            </select>
        </div>

        <div class="form-check mt-2">
            <input type="checkbox" name="buat_survey_baru" class="form-check-input" id="buatSurveyBaru{{ $r->id }}">
            <label class="form-check-label" for="buatSurveyBaru{{ $r->id }}">
                Buat Survey Rapat Baru
            </label>
        </div>

        @if($r->surveys->isNotEmpty())
            <div class="form-group text-center">
                <label>QR Code Survey</label><br>
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate(route('admin.survey-rapat.show', $r->surveys->first()->slug)) !!}
                <p class="mt-2">
                <a href="{{ route('admin.survey-rapat.show', $r->surveys->first()->slug) }}" target="_blank">
                    Buka Survey
                </a>
                </p>
            </div>
            @endif

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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Untuk create
        const jenisCreate = document.querySelector('select[name="jenis_rapat"]');
        const instansiWrapperCreate = document.getElementById('jumlahInstansiWrapperCreate');

        jenisCreate.addEventListener('change', function() {
            if (this.value === 'Eksternal') {
            instansiWrapperCreate.style.display = 'block';
            } else {
            instansiWrapperCreate.style.display = 'none';
            }
        });

        // Untuk edit (loop semua rapat)
        @foreach($rapat as $r)
            const jenisSelect{{ $r->id }} = document.querySelector('#editRapatModal{{ $r->id }} select[name="jenis_rapat"]');
            const instansiWrapper{{ $r->id }} = document.getElementById('jumlahInstansiWrapper{{ $r->id }}');

            jenisSelect{{ $r->id }}.addEventListener('change', function() {
            if (this.value === 'Eksternal') {
                instansiWrapper{{ $r->id }}.style.display = 'block';
            } else {
                instansiWrapper{{ $r->id }}.style.display = 'none';
            }
            });
        @endforeach
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    function bindSurveyDropdown(jenisSelectId, surveySelectId, selectedSurveyId = null) {
        const jenisSelect = document.getElementById(jenisSelectId);
        const surveySelect = document.getElementById(surveySelectId);

        if (!jenisSelect || !surveySelect) return;

        function loadSurveys() {
        const tipe = jenisSelect.value.toLowerCase(); // internal / eksternal
        surveySelect.innerHTML = '<option value="">-- Pilih Survey --</option>';

        if (!tipe) return;

        // ✅ gunakan base URL + tipe
        fetch(`/admin/survey-rapat/by-tipe/${tipe}`)
            .then(res => res.json())
            .then(data => {
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.judul;
                if (selectedSurveyId && selectedSurveyId == s.id) {
                opt.selected = true;
                }
                surveySelect.appendChild(opt);
            });
            });
        }

        jenisSelect.addEventListener('change', loadSurveys);
        loadSurveys(); // initial load
    }

    // untuk create
    bindSurveyDropdown('jenisRapatCreate', 'surveySelectCreate');

    // untuk edit
    @foreach($rapat as $r)
        bindSurveyDropdown('jenisRapat{{ $r->id }}', 'surveySelect{{ $r->id }}', {{ $r->surveys->first()->id ?? 'null' }});
    @endforeach
    });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Create modal toggle
    const createCheckbox = document.getElementById('buatSurveyBaruCreate');
    const createSelect   = document.getElementById('surveySelectCreate');

    function toggleCreateSurveySelect() {
      if (!createCheckbox || !createSelect) return;
      if (createCheckbox.checked) {
        createSelect.value = '';
        createSelect.setAttribute('disabled', 'disabled');
        createSelect.classList.add('bg-light');
      } else {
        createSelect.removeAttribute('disabled');
        createSelect.classList.remove('bg-light');
      }
    }
    if (createCheckbox) {
      toggleCreateSurveySelect();
      createCheckbox.addEventListener('change', toggleCreateSurveySelect);
    }

    // Edit modals toggle (loop per rapat)
    @foreach($rapat as $r)
      (function() {
        const cb = document.getElementById('buatSurveyBaru{{ $r->id }}');
        const sel = document.getElementById('surveySelect{{ $r->id }}');
        function toggleEditSurveySelect() {
          if (!cb || !sel) return;
          if (cb.checked) {
            sel.value = '';
            sel.setAttribute('disabled', 'disabled');
            sel.classList.add('bg-light');
          } else {
            sel.removeAttribute('disabled');
            sel.classList.remove('bg-light');
          }
        }
        if (cb) {
          // Ensure correct state when modal opens
          toggleEditSurveySelect();
          cb.addEventListener('change', toggleEditSurveySelect);
        }
      })();
    @endforeach
  });
</script>
@endpush

