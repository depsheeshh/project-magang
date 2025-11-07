@extends('layouts.app')

@section('title', 'Check-in Rapat Eksternal')

@section('content')
<div class="container py-5 mt-5" style="margin-top: 70px !important;">
  <div class="card shadow-lg border-0 rounded-4 form-glass">
    <div class="card-header text-center py-4 bg-gradient-header">
      <h4 class="fw-bold text-light mb-1">
        Anda Hadir Dalam Rapat: <span class="text-warning">{{ $rapat->judul ?? 'Nama Rapat' }}</span>
      </h4>
      <p class="mb-0 small text-light opacity-75">
        Silakan isi identitas kehadiran Anda di bawah ini
      </p>
    </div>

    <div class="card-body p-5">
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif
      <form action="{{ route('tamu.rapat.checkin', [$rapat->id, $token]) }}" method="POST" class="needs-validation">
        @csrf

        <div class="row g-4 mb-4">
          <div class="col-md-6">
            <label for="email" class="form-label text-light"><i class="bi bi-envelope"></i> Email</label>
            <input type="email" name="email" id="email"
                   class="form-control form-control-lg"
                   placeholder="contoh@email.com" required
                   value="{{ old('email') }}">
          </div>

          <div class="col-md-6">
            <label for="nama" class="form-label text-light"><i class="bi bi-person"></i> Nama Lengkap</label>
            <input type="text" name="nama" id="nama"
                   class="form-control form-control-lg"
                   placeholder="Nama lengkap Anda" required
                   value="{{ old('nama') }}">
          </div>

          <div class="col-md-6">
            <label for="instansi_id" class="form-label text-light">
                <i class="bi bi-building"></i> Instansi
            </label>
            <select name="instansi_id" id="instansi_id"
                    class="form-select form-select-lg" required>
                <option value="">-- Pilih Instansi --</option>
                @foreach($instansiList as $undangan)
                @php
                    $kuota = $undangan->kuota;
                    $hadir = $undangan->jumlah_hadir;
                    $sisa  = max(0, $kuota - $hadir);
                    $penuh = $sisa <= 0;
                @endphp
                <option value="{{ $undangan->instansi->id }}"
                        @if($penuh) disabled class="text-muted" @endif>
                    {{ $undangan->instansi->nama_instansi }}
                    (Kuota: {{ $kuota }}, Hadir: {{ $hadir }}, Sisa: {{ $sisa }})
                    @if($penuh) - [Penuh] @endif
                </option>
                @endforeach
            </select>
            </div>

          <div class="col-md-6">
            <label for="jabatan" class="form-label text-light"><i class="bi bi-briefcase"></i> Jabatan</label>
            <input type="text" name="jabatan" id="jabatan"
                   class="form-control form-control-lg"
                   placeholder="Contoh: Kepala Seksi, Staf, Dll" required
                   value="{{ old('jabatan') }}">
          </div>
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-lg px-5 py-3 fw-bold shadow btn-checkin">
            <i class="fas fa-sign-in-alt me-2"></i> Check-in Sekarang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
body {
  scroll-margin-top: 120px;
  background: radial-gradient(circle at top, #031f4b, #010b1d);
}

.form-glass {
  background: linear-gradient(135deg, rgba(36, 65, 130, 0.8), rgba(12, 30, 70, 0.9));
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: #f0f6ff;
  box-shadow: 0 0 25px rgba(59, 130, 246, 0.25);
}

.bg-gradient-header {
  background: linear-gradient(135deg, #3b82f6, #06b6d4);
  border: none;
  border-radius: 14px 14px 0 0;
  box-shadow: inset 0 -2px 12px rgba(255,255,255,0.15);
}

/* Input dan select cerah */
.form-control, .form-select {
  background: rgba(10, 48, 128, 0.95);
  border: 1px solid rgba(255,255,255,0.25);
  color: #fff;
  border-radius: 10px;
  transition: all 0.3s ease;
}
.form-control::placeholder, .form-select option {
  color: rgba(255,255,255,0.7);
}
.form-control:focus, .form-select:focus {
  background: rgba(255, 255, 255, 0.25);
  border-color: #38bdf8;
  box-shadow: 0 0 15px rgba(56,189,248,0.5);
}
.form-select, .form-select option {
  background-color: rgba(10, 48, 128, 0.95); /* gelap */
  color: #fff; /* teks putih */
}
.form-select option:disabled {
  color: #aaa;
  background-color: #333;
}
.form-select option:checked {
  background-color: #2563eb; /* biru */
  color: #fff;
}
.form-select option:hover {
  background-color: #1e293b; /* abu gelap */
  color: #fff;
}


/* Tombol utama cerah */
.btn-checkin {
  background: linear-gradient(135deg, #10b981, #06b6d4);
  color: #fff;
  border-radius: 50px;
  box-shadow: 0 0 25px rgba(56,189,248,0.35);
  transition: all 0.3s ease-in-out;
}
.btn-checkin:hover {
  background: linear-gradient(135deg, #22c55e, #3b82f6);
  box-shadow: 0 0 40px rgba(59,130,246,0.6);
  transform: translateY(-3px);
}
</style>

<script>
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(pos) {
    document.getElementById('latitude').value = pos.coords.latitude;
    document.getElementById('longitude').value = pos.coords.longitude;
  }, function() {
    alert("Aktifkan GPS agar lokasi Anda terdeteksi.");
  });
}
</script>
@endsection
