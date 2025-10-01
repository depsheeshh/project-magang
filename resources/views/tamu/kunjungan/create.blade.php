@extends('layouts.admin')

@section('title','Tambah Kunjungan')
@section('page-title','Form Tambah Kunjungan')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Tambah Kunjungan Baru</h4>
  </div>
  <div class="card-body">
    <form action="{{ route('tamu.kunjungan.store') }}" method="POST">
      @csrf

      {{-- Instansi --}}
      <div class="form-group">
        <label for="instansi">Instansi (opsional)</label>
        <input type="text" name="instansi" id="instansi"
               class="form-control @error('instansi') is-invalid @enderror"
               value="{{ old('instansi') }}">
        @error('instansi') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- No HP --}}
      <div class="form-group">
        <label for="no_hp">No HP (opsional)</label>
        <input type="text" name="no_hp" id="no_hp"
               class="form-control @error('no_hp') is-invalid @enderror"
               value="{{ old('no_hp') }}">
        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Alamat --}}
      <div class="form-group">
        <label for="alamat">Alamat (opsional)</label>
        <textarea name="alamat" id="alamat" rows="2"
                  class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Bidang --}}
      <div class="form-group">
        <label for="bidang_id">Pilih Bidang</label>
        <select name="bidang_id" id="bidang_id" class="form-control">
          <option value="">-- Pilih Bidang --</option>
          @foreach(\App\Models\Bidang::all() as $bidang)
            <option value="{{ $bidang->id }}">{{ $bidang->nama_bidang }}</option>
          @endforeach
        </select>
      </div>

      {{-- Pegawai --}}
      <div class="form-group">
        <label for="pegawai_id">Pilih Pegawai Tujuan</label>
        <select name="pegawai_id" id="pegawai_id"
                class="form-control @error('pegawai_id') is-invalid @enderror">
          <option value="">-- Pilih Pegawai --</option>
        </select>
        @error('pegawai_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Keperluan --}}
      <div class="form-group">
        <label for="keperluan">Keperluan</label>
        <textarea name="keperluan" id="keperluan" rows="3"
                  class="form-control @error('keperluan') is-invalid @enderror">{{ old('keperluan') }}</textarea>
        @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Simpan Kunjungan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // AJAX filter pegawai berdasarkan bidang
  document.getElementById('bidang_id').addEventListener('change', function() {
    let bidangId = this.value;
    let pegawaiSelect = document.getElementById('pegawai_id');
    pegawaiSelect.innerHTML = '<option value="">-- Memuat pegawai... --</option>';

    if(bidangId) {
      fetch(`/tamu/get-pegawai/${bidangId}`)
        .then(res => res.json())
        .then(data => {
          pegawaiSelect.innerHTML = '<option value="">-- Pilih Pegawai --</option>';
          data.forEach(p => {
            pegawaiSelect.innerHTML += `<option value="${p.id}">${p.user.name}</option>`;
          });
        })
        .catch(err => {
          pegawaiSelect.innerHTML = '<option value="">Gagal memuat pegawai</option>';
        });
    } else {
      pegawaiSelect.innerHTML = '<option value="">-- Pilih Pegawai --</option>';
    }
  });
</script>
@endpush
