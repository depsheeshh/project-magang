@extends('layouts.app')

@section('title','Form Buku Tamu')
@section('content')
<div class="container mt-5 py-5">
  <h2>Form Buku Tamu</h2>
  <form class="mt-3" action="{{ route('tamu.store') }}" method="POST">
    @csrf

    {{-- Nama & Email otomatis dari user login --}}
    <div class="form-group mb-4">
      <label>Nama Lengkap</label>
      <input type="text" class="form-control" value="{{ $user->name }}" disabled>
      <input type="hidden" name="nama" value="{{ $user->name }}">
    </div>

    <div class="form-group mb-4">
      <label>Email</label>
      <input type="email" class="form-control" value="{{ $user->email }}" disabled>
      <input type="hidden" name="email" value="{{ $user->email }}">
    </div>

    <div class="form-group mb-4">
      <label>Instansi / Perusahaan</label>
      <input type="text" name="instansi" class="form-control">
    </div>

    <div class="form-group mb-4">
      <label>No HP</label>
      <input type="tel" name="no_hp" class="form-control">
    </div>

    <div class="form-group mb-4">
      <label>Alamat</label>
      <textarea name="alamat" class="form-control"></textarea>
    </div>

    <div class="form-group mb-4">
      <label>Keperluan</label>
      <textarea name="keperluan" class="form-control" required></textarea>
    </div>

    <div class="form-group mb-4">
      <label>Tujuan Bidang</label>
      <select id="bidang" class="form-control" required>
        <option value="">-- Pilih Bidang --</option>
        @foreach($bidang as $b)
          <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
        @endforeach
      </select>
    </div>

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

