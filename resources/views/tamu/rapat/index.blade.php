@extends('layouts.admin')

@section('title','Rapat Saya')
@section('page-title','Rapat Saya')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Daftar Rapat Saya</h4>
  </div>
  <div class="card-body">
    @forelse($rapatSaya as $rapat)
      @php
        $undangan = $rapat->undangan->first();
      @endphp
      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <h5 class="mb-2">{{ $rapat->judul }}</h5>
          <p class="mb-1">
            <span class="badge badge-info">
              {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
            </span>
            s/d
            <span class="badge badge-secondary">
              {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
            </span>
          </p>
          <p class="mb-3">
            Lokasi: {{ $rapat->lokasi ?? '-' }} <br>
            <small class="text-muted">
              Lat: {{ $rapat->latitude ?? '-' }},
              Lon: {{ $rapat->longitude ?? '-' }},
              Radius: {{ $rapat->radius ?? '-' }} m
            </small>
          </p>

          {{-- Data peserta diri sendiri --}}
          @if($undangan)
            <p class="mb-2">
              <strong>Peserta:</strong> {{ $undangan->user->name }} <br>
              <strong>Instansi:</strong> {{ $undangan->user->instansi->nama_instansi ?? '-' }}
              @if($undangan->user->instansi_id)
                <button class="btn btn-sm btn-outline-primary ml-2 mb-2" data-toggle="modal" data-target="#updateInstansiModal{{ $rapat->id }}">
                  <i class="fas fa-edit"></i> Ganti Instansi
                </button>
              @endif
              <br>
              <strong>Status Kehadiran:</strong>
              @if($undangan->status_kehadiran === 'pending')
                <span class="badge badge-warning">Belum Check-in</span>
              @elseif($undangan->status_kehadiran === 'hadir')
                <span class="badge badge-success">Sudah Check-in</span>
                <small class="text-muted d-block">
                  {{ $undangan->checked_in_at ? $undangan->checked_in_at->format('d-m-Y H:i') : '' }}
                </small>
              @else
                <span class="badge badge-danger">Tidak Hadir</span>
              @endif
            </p>
          @endif

          {{-- Aksi --}}
          @if($undangan && $undangan->status_kehadiran === 'pending')
            <a href="{{ route('tamu.rapat.show', $rapat->id) }}" class="btn btn-info btn-sm">
              <i class="fas fa-info-circle"></i> Detail & Check-in
            </a>
          @endif
        </div>
      </div>

      @section('modals')
      {{-- Modal Update Instansi --}}
      <div class="modal fade" id="updateInstansiModal{{ $rapat->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content">
            <form method="POST" action="{{ route('tamu.rapat.updateInstansi') }}">
              @csrf
              <input type="hidden" name="rapat_id" value="{{ $rapat->id }}">
              <input type="hidden" name="mode" id="modeUpdate{{ $rapat->id }}" value="select">

              <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-sync-alt"></i> Ganti Instansi</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              </div>

              <div class="modal-body">
                {{-- Toggle mode --}}
                <div class="btn-group btn-group-toggle mb-3 w-100" data-toggle="buttons">
                  <label class="btn btn-outline-primary active w-50" id="btnModeSelectUpdate{{ $rapat->id }}">
                    <input type="radio" checked> <i class="fas fa-list"></i> Pilih Instansi yang Tersedia
                  </label>
                  <label class="btn btn-outline-secondary w-50" id="btnModeManualUpdate{{ $rapat->id }}">
                    <input type="radio"> <i class="fas fa-pen"></i> Isi Manual
                  </label>
                </div>

                {{-- Box pilih instansi --}}
                <div id="selectAdminBoxUpdate{{ $rapat->id }}" class="fade-slide show">
                <div class="form-group">
                    <label for="instansi_admin_id_update{{ $rapat->id }}">
                    <i class="fas fa-search"></i> Cari & Pilih Instansi
                    </label>
                    <select name="instansi_admin_id" id="instansi_admin_id_update{{ $rapat->id }}" class="form-control">
                    <option value="">-- Pilih Instansi --</option>
                    </select>
                </div>
                {{-- Preview readonly --}}
                <div class="row mt-2">
                    <div class="col-md-6">
                    <label>Nama Instansi</label>
                    <input type="text" id="namaInstansiPreviewUpdate{{ $rapat->id }}" class="form-control" readonly>
                    </div>
                    <div class="col-md-6">
                    <label>Alamat / Lokasi</label>
                    <input type="text" id="lokasiPreviewUpdate{{ $rapat->id }}" class="form-control" readonly>
                    </div>
                </div>
                </div>

                {{-- Box manual --}}
                <div id="manualBoxUpdate{{ $rapat->id }}" class="fade-slide d-none">
                <div class="form-group">
                    <label for="nama_instansi_update{{ $rapat->id }}">
                    <i class="fas fa-building"></i> Nama Instansi
                    </label>
                    <input type="text" name="nama_instansi" id="nama_instansi_update{{ $rapat->id }}" class="form-control">
                </div>
                <div class="form-group">
                    <label for="lokasi_update{{ $rapat->id }}">
                    <i class="fas fa-map-marker-alt"></i> Lokasi
                    </label>
                    <input type="text" name="lokasi" id="lokasi_update{{ $rapat->id }}" class="form-control">
                </div>
                </div>

              </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan Perubahan</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      @endsection
    @empty
      <p class="text-muted">Anda belum memiliki undangan rapat.</p>
    @endforelse
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  @foreach($rapatSaya as $rapat)
    (function() {
      const rapatId = "{{ $rapat->id }}";
      const selectEl = document.getElementById('instansi_admin_id_update' + rapatId);
      const namaPreview = document.getElementById('namaInstansiPreviewUpdate' + rapatId);
      const lokasiPreview = document.getElementById('lokasiPreviewUpdate' + rapatId);

      // Fetch instansi tersedia
      fetch('{{ route('tamu.api.instansi.admin') }}')
        .then(res => res.json())
        .then(data => {
          data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item.id;
            opt.textContent = item.nama_instansi + (item.lokasi ? ' — ' + item.lokasi : '');
            opt.dataset.nama = item.nama_instansi;
            opt.dataset.lokasi = item.lokasi ?? '';
            // auto-select instansi user saat ini
            if ("{{ auth()->user()->instansi_id }}" == item.id) {
              opt.selected = true;
              namaPreview.value = item.nama_instansi;
              lokasiPreview.value = item.lokasi ?? '';
            }
            selectEl.appendChild(opt);
          });
        });

      // Auto preview saat pilih instansi
      selectEl.addEventListener('change', () => {
        const opt = selectEl.selectedOptions[0];
        if (!opt || !opt.value) {
          namaPreview.value = '';
          lokasiPreview.value = '';
          return;
        }
        namaPreview.value = opt.dataset.nama;
        lokasiPreview.value = opt.dataset.lokasi;
      });

      // Toggle mode
      const btnModeSelect = document.getElementById('btnModeSelectUpdate' + rapatId);
      const btnModeManual = document.getElementById('btnModeManualUpdate' + rapatId);
      const selectBox = document.getElementById('selectAdminBoxUpdate' + rapatId);
      const manualBox = document.getElementById('manualBoxUpdate' + rapatId);
      const modeInput = document.getElementById('modeUpdate' + rapatId);

      btnModeSelect.addEventListener('click', () => {
        modeInput.value = 'select';
        btnModeSelect.classList.add('active');
        btnModeManual.classList.remove('active');
        selectBox.classList.remove('d-none');
        manualBox.classList.add('d-none');
        });

      btnModeManual.addEventListener('click', () => {
        modeInput.value = 'manual';
        btnModeManual.classList.add('active');
        btnModeSelect.classList.remove('active');
        manualBox.classList.remove('d-none');
        selectBox.classList.add('d-none');

        // Prefill field manual dengan instansi user saat ini
        const currentNama = "{{ $undangan->user->instansi->nama_instansi ?? '' }}";
        const currentLokasi = "{{ $undangan->user->instansi->lokasi ?? '' }}";

        document.getElementById('nama_instansi_update' + rapatId).value = currentNama;
        document.getElementById('lokasi_update' + rapatId).value = currentLokasi;
        });

    })();
  @endforeach
});
</script>
@endpush



