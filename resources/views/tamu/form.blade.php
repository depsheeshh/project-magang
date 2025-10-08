@extends('layouts.app')

@section('title','Form Buku Tamu')
@section('content')
<div class="container mt-5 py-5">
  <h2>Form Buku Tamu</h2>
  <form class="mt-3" action="{{ route('tamu.store') }}" method="POST">
    @csrf

    {{-- Nama & Email otomatis --}}
    <div class="form-group mb-4">
      <label>Nama Lengkap</label>
      <input type="text" class="form-control" value="{{ $user->name }}" disabled>
    </div>

    <div class="form-group mb-4">
      <label>Email</label>
      <input type="email" class="form-control" value="{{ $user->email }}" disabled>
    </div>

    {{-- Instansi --}}
    <div class="form-group mb-4">
      <label>Instansi / Perusahaan</label>
      <input type="text" name="instansi" class="form-control"
             value="{{ old('instansi', $tamu->instansi ?? '') }}"
             @if($tamu && $tamu->instansi) disabled @endif>
      @if($tamu && $tamu->instansi)
        <input type="hidden" name="instansi" value="{{ $tamu->instansi }}">
      @endif
    </div>

    {{-- No HP --}}
    <div class="form-group mb-4">
      <label>No HP</label>
      <input type="tel" name="no_hp" class="form-control"
             value="{{ old('no_hp', $tamu->no_hp ?? '') }}"
             @if($tamu && $tamu->no_hp) disabled @endif>
      @if($tamu && $tamu->no_hp)
        <input type="hidden" name="no_hp" value="{{ $tamu->no_hp }}">
      @endif
    </div>

    {{-- Alamat --}}
    <div class="form-group mb-4">
      <label>Alamat</label>
      <textarea name="alamat" class="form-control"
                @if($tamu && $tamu->alamat) disabled @endif>{{ old('alamat', $tamu->alamat ?? '') }}</textarea>
      @if($tamu && $tamu->alamat)
        <input type="hidden" name="alamat" value="{{ $tamu->alamat }}">
      @endif
    </div>

    {{-- Keperluan --}}
    <div class="form-group mb-4">
      <label>Keperluan</label>
      <textarea name="keperluan" class="form-control" required>{{ old('keperluan') }}</textarea>
    </div>

    {{-- Tujuan Bidang --}}
    <div class="form-group mb-4">
      <label>Tujuan Bidang</label>
      <select id="bidang" class="form-control" required>
        <option value="">-- Pilih Bidang --</option>
        @foreach($bidang as $b)
          <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
        @endforeach
      </select>
    </div>

    {{-- Tujuan Pegawai --}}
    <div class="form-group mb-4">
      <label>Tujuan Pegawai</label>
      <select name="pegawai_id" id="pegawai" class="form-control" required>
        <option value="">-- Pilih Pegawai --</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Kirim</button>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('bidang').addEventListener('change', function() {
    let bidangId = this.value;
    let pegawaiSelect = document.getElementById('pegawai');
    pegawaiSelect.innerHTML = '<option value="">-- Pilih Pegawai --</option>';

    if (bidangId) {
        fetch(`/tamu/get-pegawai/${bidangId}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(p => {
                    let option = document.createElement('option');
                    option.value = p.id;
                    option.textContent = p.user.name;
                    pegawaiSelect.appendChild(option);
                });
            });
    }
});
</script>
@endpush
