@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

@push('style')
<style>
/* 🌙 Dark Mode */
body.dark-mode {
  background-color: #121212;
  color: #e0e0e0;
}

/* Card umum */
body.dark-mode .card {
  background-color: #1e1e1e;
  border-color: #333;
  color: #e0e0e0;
}

/* Header card */
body.dark-mode .card-header {
  background-color: #2a2a2a;
  border-bottom: 1px solid #333;
  color: #f5f5f5;
}

/* Body card */
body.dark-mode .card-body {
  background-color: #1e1e1e;
  color: #e0e0e0;
}

/* List group */
body.dark-mode .list-group-item {
  background-color: #1e1e1e;
  border-color: #333;
  color: #e0e0e0;
}

/* Badge tetap kontras */
body.dark-mode .badge {
  color: #fff !important;
}
</style>
@endpush


{{-- Dashboard untuk Admin --}}
@if($role === 'admin')
<div class="row">
  {{-- User --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total User</h4></div>
        <div class="card-body">{{ $totalUsers }}</div>
      </div>
    </div>
  </div>

  {{-- Pegawai --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success"><i class="fas fa-id-card"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Pegawai</h4></div>
        <div class="card-body">{{ $totalPegawai }}</div>
      </div>
    </div>
  </div>

  {{-- Bidang --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning"><i class="fas fa-sitemap"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Bidang</h4></div>
        <div class="card-body">{{ $totalBidang }}</div>
      </div>
    </div>
  </div>

  {{-- Jabatan --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-danger"><i class="fas fa-briefcase"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Jabatan</h4></div>
        <div class="card-body">{{ $totalJabatan }}</div>
      </div>
    </div>
  </div>

  {{-- Survey --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-info"><i class="fas fa-comment-dots"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Survey</h4></div>
        <div class="card-body">{{ $totalSurvey }}</div>
      </div>
    </div>
  </div>

  {{-- Rapat --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-secondary"><i class="fas fa-handshake"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Rapat</h4></div>
        <div class="card-body">{{ $totalRapat }}</div>
      </div>
    </div>
  </div>

  {{-- Instansi --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-dark"><i class="fas fa-building"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Instansi</h4></div>
        <div class="card-body">{{ $totalInstansi }}</div>
      </div>
    </div>
  </div>
</div>
@endif

{{-- Dashboard untuk Frontliner --}}
@if($role === 'frontliner')
<div class="row">
  {{-- Total --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Kunjungan</h4></div>
        <div class="card-body">{{ $total }}</div>
      </div>
    </div>
  </div>

  {{-- Diterima --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success"><i class="fas fa-check"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Diterima</h4></div>
        <div class="card-body">{{ $diterima }}</div>
      </div>
    </div>
  </div>

  {{-- Ditolak --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-danger"><i class="fas fa-times"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Ditolak</h4></div>
        <div class="card-body">{{ $ditolak }}</div>
      </div>
    </div>
  </div>

  {{-- Sedang Bertamu --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning"><i class="fas fa-door-open"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Sedang Bertamu</h4></div>
        <div class="card-body">{{ $sedangBertamu }}</div>
      </div>
    </div>
  </div>

  {{-- Selesai --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-info"><i class="fas fa-flag-checkered"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Selesai</h4></div>
        <div class="card-body">{{ $selesai }}</div>
      </div>
    </div>
  </div>
</div>

{{-- Daftar kunjungan menunggu --}}
<div class="card mt-4">
  <div class="card-header"><h4>Daftar Kunjungan Menunggu</h4></div>
  <div class="card-body">
    @if($kunjunganMenunggu->isEmpty())
      <div class="alert alert-info">Belum ada kunjungan menunggu.</div>
    @else
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Nama Tamu</th>
              <th>Bidang</th>
              <th>Pegawai</th>
              <th>Keperluan</th>
              <th>Waktu Masuk</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($kunjunganMenunggu as $k)
              <tr>
                <td>{{ $k->tamu->nama ?? $k->tamu->user->name ?? '-' }}</td>
                <td>{{ $k->pegawai->bidang->nama_bidang ?? '-' }}</td>
                <td>{{ $k->pegawai->user->name ?? '-' }}</td>
                <td>{{ $k->keperluan }}</td>
                <td>{{ \Carbon\Carbon::parse($k->waktu_masuk)->format('d/m/Y H:i') }}</td>
                <td><span class="badge badge-warning">Menunggu</span></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>
@endif



{{-- Dashboard untuk Pegawai --}}
@if($role === 'pegawai')
<div class="row">

  {{-- Ringkasan cepat --}}
  <div class="col-md-3 mb-3">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Kunjungan</h4></div>
        <div class="card-body">{{ $totalKunjungan ?? 0 }}</div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Selesai</h4></div>
        <div class="card-body">{{ $selesai ?? 0 }}</div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning"><i class="fas fa-user-clock"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Sedang Bertamu</h4></div>
        <div class="card-body">{{ $sedangBertamu ?? 0 }}</div>
      </div>
    </div>
  </div>

  <div class="col-md-3 mb-3">
    <div class="card card-statistic-1">
      <div class="card-icon bg-danger"><i class="fas fa-times-circle"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Ditolak</h4></div>
        <div class="card-body">{{ $ditolakPegawai ?? 0 }}</div>
      </div>
    </div>
  </div>

  {{-- Kunjungan terbaru --}}
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Kunjungan Terbaru</h4>
        <a href="{{ route('pegawai.kunjungan.notifikasi') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        @if($kunjunganTerbaru->isEmpty())
          <div class="text-center text-muted py-4">
            <i class="fas fa-inbox fa-2x mb-2"></i>
            <p class="mb-0">Belum ada kunjungan terbaru</p>
          </div>
        @else
          <ul class="list-group list-group-flush">
            @foreach($kunjunganTerbaru as $k)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $k->tamu->nama ?? '-' }}</strong>
                  <small class="text-muted">— {{ $k->keperluan }}</small>
                </div>
                <span class="badge
                  @if($k->status === 'selesai') badge-success
                  @elseif($k->status === 'sedang_bertamu') badge-warning
                  @elseif($k->status === 'ditolak') badge-danger
                  @else badge-secondary @endif">
                  {{ ucfirst(str_replace('_',' ',$k->status)) }}
                </span>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>

  {{-- Riwayat singkat --}}
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Riwayat Singkat</h4>
        <a href="{{ route('pegawai.kunjungan.riwayat') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        @if(isset($riwayatSingkat) && $riwayatSingkat->isEmpty())
          <div class="text-center text-muted py-4">
            <i class="fas fa-history fa-2x mb-2"></i>
            <p class="mb-0">Belum ada riwayat kunjungan</p>
          </div>
        @else
          <ul class="list-group list-group-flush">
            @foreach($riwayatSingkat ?? [] as $r)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $r->tamu->nama ?? '-' }}</strong>
                  <small class="text-muted">— {{ $r->keperluan }}</small>
                </div>
                <span class="text-success small">{{ $r->waktu_keluar }}</span>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>

</div>
@endif



{{-- Dashboard untuk Tamu --}}
@if($role === 'tamu')
<div class="row">
  {{-- Total Kunjungan --}}
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Kunjungan Saya</h4></div>
        <div class="card-body">{{ $total }}</div>
      </div>
    </div>
  </div>

  {{-- Kunjungan Diterima --}}
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success"><i class="fas fa-check"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Kunjungan Diterima</h4></div>
        <div class="card-body">{{ $diterima }}</div>
      </div>
    </div>
  </div>

  {{-- Kunjungan Ditolak --}}
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-danger"><i class="fas fa-times"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Kunjungan Ditolak</h4></div>
        <div class="card-body">{{ $ditolak }}</div>
      </div>
    </div>
  </div>

  {{-- Undangan Rapat --}}
  <div class="col-lg-6 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning"><i class="fas fa-envelope-open-text"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Undangan Rapat</h4></div>
        <div class="card-body">{{ $undanganRapat }}</div>
      </div>
    </div>
  </div>
</div>
@endif

@endsection
