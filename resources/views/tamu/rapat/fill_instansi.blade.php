@extends('layouts.admin')

@section('title','Isi Data Instansi')
@section('page-title','Isi Data Instansi')

@push('styles')
<style>
/* Variabel warna adaptif */
:root {
  --instansi-bg: #f8f9fa;
  --instansi-text: #212529;
  --instansi-border: #dee2e6;
  --instansi-focus: #0d6efd; /* biru Bootstrap */
}

@media (prefers-color-scheme: dark) {
  :root {
    --instansi-bg: #1e1e1e;
    --instansi-text: #e9ecef;
    --instansi-border: #444;
    --instansi-focus: #20c997; /* hijau tosca untuk dark mode */
  }
}

/* Kotak form */
#selectAdminBox, #manualBox {
  background-color: var(--instansi-bg);
  color: var(--instansi-text);
  border: 1px solid var(--instansi-border);
  border-radius: .75rem;
  padding: 1.25rem;
  margin-bottom: 1.5rem;
  transition: background-color .3s, color .3s, border-color .3s;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

/* Label */
#selectAdminBox label,
#manualBox label {
  font-weight: 600;
  color: var(--instansi-text);
}

/* Input & Select */
#selectAdminBox input,
#manualBox input,
#selectAdminBox select,
#manualBox select {
  background-color: var(--instansi-bg);
  color: var(--instansi-text);
  border: 1px solid var(--instansi-border);
  transition: all .25s ease;
}

/* Hover & Focus efek glowing */
#selectAdminBox input:focus,
#manualBox input:focus,
#selectAdminBox select:focus,
#manualBox select:focus {
  border-color: var(--instansi-focus);
  box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25); /* default biru */
  outline: none;
}

/* Dark mode glow */
@media (prefers-color-scheme: dark) {
  #selectAdminBox input:focus,
  #manualBox input:focus,
  #selectAdminBox select:focus,
  #manualBox select:focus {
    box-shadow: 0 0 0 0.25rem rgba(32,201,151,.35); /* hijau glow */
  }
}

/* Tombol toggle mode */
.btn-group-toggle .btn {
  transition: all .25s ease;
}
.btn-group-toggle .btn.active {
  background-color: var(--instansi-focus);
  color: #fff !important;
  border-color: var(--instansi-focus);
}
</style>
@endpush



@section('content')
<div class="card shadow-sm">
  <div class="card-header bg-gradient-primary text-white">
    <h4 class="mb-0"><i class="fas fa-building"></i> Lengkapi Data Instansi Anda</h4>
  </div>
  <div class="card-body">


    <form action="{{ route('tamu.rapat.storeInstansi') }}" method="POST" id="instansiForm">
      @csrf
      <input type="hidden" name="rapat_id" value="{{ $rapat->id }}">
      <input type="hidden" name="mode" id="mode" value="select"> {{-- default: pilih instansi tersedia --}}

      {{-- Toggle mode --}}
      <div class="btn-group btn-group-toggle mb-4 w-100" data-toggle="buttons">
        <label class="btn btn-outline-primary active w-50" id="btnModeSelect">
          <input type="radio" checked> <i class="fas fa-list"></i> Pilih Instansi yang Tersedia
        </label>
        <label class="btn btn-outline-secondary w-50" id="btnModeManual">
          <input type="radio"> <i class="fas fa-pen"></i> Isi Manual
        </label>
      </div>

      {{-- Pilih instansi tersedia --}}
      <div id="selectAdminBox" class="rounded shadow-sm">
        <div class="form-group">
          <label for="instansi_admin_id"><i class="fas fa-search"></i> Cari & Pilih Instansi</label>
          <select name="instansi_admin_id" id="instansi_admin_id" class="form-control" required>
            <option value="">-- Pilih Instansi --</option>
            {{-- opsi diisi via JS --}}
          </select>
          <small class="form-text text-muted">Instansi yang sudah terdaftar akan muncul di sini.</small>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label>Nama Instansi</label>
            <input type="text" id="namaInstansiPreview" class="form-control" readonly>
          </div>
          <div class="col-md-6">
            <label>Alamat / Lokasi</label>
            <input type="text" id="lokasiPreview" class="form-control" readonly>
          </div>
        </div>
      </div>

      {{-- Isi manual --}}
      <div id="manualBox" class="rounded shadow-sm d-none">
        <div class="form-group">
          <label for="nama_instansi"><i class="fas fa-building"></i> Nama Instansi <span class="text-danger">*</span></label>
          <input type="text" id="nama_instansi" name="nama_instansi"
                 class="form-control @error('nama_instansi') is-invalid @enderror"
                 placeholder="Contoh: PT. Mencari Cinta Sejati"
                 value="{{ old('nama_instansi') }}">
          @error('nama_instansi')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="form-group mt-3">
          <label for="lokasi"><i class="fas fa-map-marker-alt"></i> Alamat / Lokasi Instansi</label>
          <input type="text" id="lokasi" name="lokasi"
                 class="form-control @error('lokasi') is-invalid @enderror"
                 placeholder="Contoh: Jl. Kaki No. 12, Cirebon"
                 value="{{ old('lokasi') }}">
          @error('lokasi')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('tamu.rapat.saya') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-success">
          <i class="fas fa-save"></i> Simpan & Lanjut Check-in
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
(function(){
  const btnModeSelect = document.getElementById('btnModeSelect');
  const btnModeManual = document.getElementById('btnModeManual');
  const selectAdminBox = document.getElementById('selectAdminBox');
  const manualBox = document.getElementById('manualBox');
  const modeInput = document.getElementById('mode');

  const selectEl = document.getElementById('instansi_admin_id');
  const namaPreview = document.getElementById('namaInstansiPreview');
  const lokasiPreview = document.getElementById('lokasiPreview');

  // Toggle mode
  btnModeSelect.addEventListener('click', () => {
    modeInput.value = 'select';
    btnModeSelect.classList.add('active');
    btnModeManual.classList.remove('active');
    selectAdminBox.classList.remove('d-none');
    manualBox.classList.add('d-none');
    selectEl.setAttribute('required','required');
    document.getElementById('nama_instansi').removeAttribute('required');
  });

  btnModeManual.addEventListener('click', () => {
    modeInput.value = 'manual';
    btnModeManual.classList.add('active');
    btnModeSelect.classList.remove('active');
    manualBox.classList.remove('d-none');
    selectAdminBox.classList.add('d-none');
    selectEl.removeAttribute('required');
    document.getElementById('nama_instansi').setAttribute('required','required');
  });

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
        selectEl.appendChild(opt);
      });
    });

  // Auto preview
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
})();
</script>
@endpush
