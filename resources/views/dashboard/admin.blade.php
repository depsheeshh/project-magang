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

/* ===== Monthly Filter Bar ===== */
.month-filter-bar {
  background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
  border-radius: 12px;
  padding: 16px 20px;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
  box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
}

body.dark-mode .month-filter-bar {
  background: linear-gradient(135deg, #2a2a4a 0%, #1a1a3a 100%);
  box-shadow: 0 4px 15px rgba(0,0,0,0.4);
}

.month-filter-bar .filter-label {
  color: #fff;
  font-weight: 600;
  font-size: 14px;
  white-space: nowrap;
  display: flex;
  align-items: center;
  gap: 8px;
}

.month-filter-bar .filter-label i {
  font-size: 16px;
  opacity: 0.9;
}

.month-filter-bar select {
  border: none;
  border-radius: 8px;
  padding: 8px 14px;
  font-size: 14px;
  font-weight: 500;
  background: rgba(255,255,255,0.18);
  color: #fff;
  cursor: pointer;
  outline: none;
  transition: background 0.2s;
  backdrop-filter: blur(4px);
  min-width: 110px;
}

.month-filter-bar select option {
  background: #2c3e50;
  color: #fff;
}

.month-filter-bar select:hover,
.month-filter-bar select:focus {
  background: rgba(255,255,255,0.28);
}

.month-filter-bar .btn-filter {
  background: #fff;
  color: #4e73df;
  border: none;
  border-radius: 8px;
  padding: 8px 20px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.month-filter-bar .btn-filter:hover {
  background: #f0f0ff;
  transform: translateY(-1px);
  box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.month-filter-bar .period-badge {
  background: rgba(255,255,255,0.15);
  color: #fff;
  border-radius: 20px;
  padding: 5px 14px;
  font-size: 13px;
  font-weight: 500;
  margin-left: auto;
  white-space: nowrap;
  border: 1px solid rgba(255,255,255,0.25);
}

/* Animasi card stats */
.card-statistic-1 {
  transition: transform 0.2s, box-shadow 0.2s;
}
.card-statistic-1:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}
</style>
@endpush


{{-- ===== FILTER BULAN / TAHUN (tampil untuk semua role) ===== --}}
<form method="GET" action="{{ route('dashboard.index') }}" id="filterForm">
  <div class="month-filter-bar">
    <span class="filter-label">
      <i class="fas fa-calendar-alt"></i>
      Filter Periode:
    </span>

    <select name="bulan" onchange="this.form.submit()" title="Pilih Bulan">
      @foreach($bulanList as $num => $nama)
        <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
      @endforeach
    </select>

    <select name="tahun" onchange="this.form.submit()" title="Pilih Tahun">
      @foreach(array_reverse($tahunList) as $t)
        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
      @endforeach
    </select>

    <span class="period-badge">
      <i class="fas fa-chart-bar me-1"></i>
      Data: {{ $bulanList[$bulan] }} {{ $tahun }}
    </span>
  </div>
</form>


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

  {{-- Tamu --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-info"><i class="fas fa-user-friends"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Tamu</h4></div>
        <div class="card-body">{{ $totalTamu }}</div>
      </div>
    </div>
  </div>

  {{-- Total Kunjungan Tamu (per bulan) --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning"><i class="fas fa-user-clock"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Kunjungan Tamu</h4></div>
        <div class="card-body">{{ $totalKunjunganTamu }}</div>
      </div>
    </div>
  </div>

  {{-- Survey (per bulan) --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-info"><i class="fas fa-comment-dots"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Survey Tamu</h4></div>
        <div class="card-body">{{ $totalSurvey }}</div>
      </div>
    </div>
  </div>

  {{-- Rapat (per bulan) --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-secondary"><i class="fas fa-handshake"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Rapat Dibuat</h4></div>
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

  {{-- Survey Rapat Terisi (per bulan) --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Survey Rapat Terisi</h4></div>
        <div class="card-body">{{ $surveyRapatFilled }}</div>
      </div>
    </div>
  </div>

  {{-- Apel Pagi (per bulan) --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-info"><i class="fas fa-sun"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Apel Pagi</h4></div>
        <div class="card-body">{{ $apelTotal }}</div>
      </div>
    </div>
  </div>

</div>

{{-- Info periode yang ditampilkan --}}
<div class="alert alert-info mt-2 mb-3 py-2 px-3" style="border-radius:8px; font-size:13px;">
  <i class="fas fa-info-circle me-1"></i>
  Data <strong>Kunjungan Tamu, Survey, Rapat, Survey Rapat Terisi, dan Apel Pagi</strong>
  menampilkan total <strong>{{ $bulanList[$bulan] }} {{ $tahun }}</strong>.
  Data <strong>User, Pegawai, Bidang, Jabatan, Tamu, dan Instansi</strong> adalah total keseluruhan.
</div>

<div class="row mt-3">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header"><h4 class="mb-0">📊 Grafik Statistik Rapat & Kunjungan — {{ $bulanList[$bulan] }} {{ $tahun }}</h4></div>
      <div class="card-body">
        <canvas id="chartAdmin" height="120"></canvas>
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

  {{-- Rapat --}}
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-secondary"><i class="fas fa-handshake"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Daftar Rapat</h4></div>
        <div class="card-body">{{ $totalRapat }}</div>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-info mt-2 mb-3 py-2 px-3" style="border-radius:8px; font-size:13px;">
  <i class="fas fa-info-circle me-1"></i>
  Statistik kunjungan & rapat periode <strong>{{ $bulanList[$bulan] }} {{ $tahun }}</strong>.
  Daftar kunjungan menunggu di bawah menampilkan semua status terkini (real-time).
</div>

{{-- Daftar kunjungan menunggu --}}
<div class="card mt-2">
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
<div class="row mt-5">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header"><h4 class="mb-0">📊 Statistik Kunjungan Frontliner — {{ $bulanList[$bulan] }} {{ $tahun }}</h4></div>
      <div class="card-body">
        <canvas id="chartFrontliner" height="120"></canvas>
      </div>
    </div>
  </div>
</div>
@endif



{{-- Dashboard untuk Pegawai --}}
@if($role === 'pegawai')
{{-- Baris 1: Statistik Kunjungan --}}
<div class="row">
  @foreach([
    ['label' => 'Total Kunjungan', 'icon' => 'users', 'bg' => 'primary', 'value' => $totalKunjungan ?? 0],
    ['label' => 'Selesai', 'icon' => 'check-circle', 'bg' => 'success', 'value' => $selesai ?? 0],
    ['label' => 'Sedang Bertamu', 'icon' => 'user-clock', 'bg' => 'warning', 'value' => $sedangBertamu ?? 0],
    ['label' => 'Ditolak', 'icon' => 'times-circle', 'bg' => 'danger', 'value' => $ditolakPegawai ?? 0],
  ] as $stat)
  <div class="col-sm-6 col-md-3 mb-3">
    <div class="card card-statistic-1">
      <div class="card-icon bg-{{ $stat['bg'] }}"><i class="fas fa-{{ $stat['icon'] }}"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>{{ $stat['label'] }}</h4></div>
        <div class="card-body">{{ $stat['value'] }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- Baris 2: Data Rapat (full-width) --}}
<div class="row">
  <div class="col-12 mb-3">
    <div class="card card-statistic-1">
      <div class="card-icon bg-secondary"><i class="fas fa-handshake"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Data Rapat</h4></div>
        <div class="card-body">{{ $totalRapatPegawai ?? 0 }}</div>
      </div>
    </div>
  </div>
</div>

<div class="alert alert-info mt-0 mb-3 py-2 px-3" style="border-radius:8px; font-size:13px;">
  <i class="fas fa-info-circle me-1"></i>
  Statistik kunjungan & rapat periode <strong>{{ $bulanList[$bulan] }} {{ $tahun }}</strong>.
  Kunjungan terbaru & riwayat singkat menampilkan 5 data terakhir (semua waktu).
</div>

{{-- Baris 3: Kunjungan Terbaru & Riwayat Singkat (di bawah Data Rapat) --}}
<div class="row">
  {{-- Kunjungan Terbaru --}}
  <div class="col-sm-12 col-md-6 mb-3">
    <div class="card shadow-sm h-100">
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

  {{-- Riwayat Singkat --}}
  <div class="col-sm-12 col-md-6 mb-3">
    <div class="card shadow-sm h-100">
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
<div class="row mt-5">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header"><h4 class="mb-0">📊 Statistik Kunjungan Pegawai — {{ $bulanList[$bulan] }} {{ $tahun }}</h4></div>
      <div class="card-body">
        <canvas id="chartPegawai" height="120"></canvas>
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

<div class="alert alert-info mt-2 mb-3 py-2 px-3" style="border-radius:8px; font-size:13px;">
  <i class="fas fa-info-circle me-1"></i>
  Menampilkan data kunjungan & undangan rapat periode <strong>{{ $bulanList[$bulan] }} {{ $tahun }}</strong>.
</div>

<div class="row mt-3">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header"><h4 class="mb-0">📊 Statistik Kunjungan Tamu — {{ $bulanList[$bulan] }} {{ $tahun }}</h4></div>
      <div class="card-body">
        <canvas id="chartTamu" height="120"></canvas>
      </div>
    </div>
  </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if($role === 'admin')
<script>
  var ctx = document.getElementById('chartAdmin').getContext('2d');
  var chartAdmin = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
          'Rapat Dibuat',
          'Survey Rapat Terisi',
          'Survey Rapat Belum',
          'Kunjungan Tamu',
          'Survey Tamu',
          'Apel Pagi'
        ],
        datasets: [{
          label: 'Statistik {{ $bulanList[$bulan] }} {{ $tahun }}',
          data: [
              {{ $totalRapat }},
              {{ $surveyRapatFilled }},
              {{ $surveyRapatPending }},
              {{ $totalKunjunganTamu }},
              {{ $totalSurvey }},
              {{ $apelTotal }}
          ],
          backgroundColor: [
              '#4e73df',
              '#1cc88a',
              '#e74a3b',
              '#36b9cc',
              '#f6c23e',
              '#fd7e14'
          ]
        }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true },
        tooltip: { enabled: true }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { stepSize: 1 }
        }
      }
    }
  });
</script>
@endif

@if($role === 'frontliner')
<script>
  var ctxFront = document.getElementById('chartFrontliner').getContext('2d');
  var chartFrontliner = new Chart(ctxFront, {
    type: 'bar',
    data: {
      labels: ['Total Kunjungan','Diterima','Ditolak','Sedang Bertamu','Selesai'],
      datasets: [{
        label: 'Statistik {{ $bulanList[$bulan] }} {{ $tahun }}',
        data: [
          {{ $total }},
          {{ $diterima }},
          {{ $ditolak }},
          {{ $sedangBertamu }},
          {{ $selesai }}
        ],
        backgroundColor: ['#4e73df','#1cc88a','#e74a3b','#f6c23e','#36b9cc']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: true } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
</script>
@endif

@if($role === 'pegawai')
<script>
  var ctxPegawai = document.getElementById('chartPegawai').getContext('2d');
  var chartPegawai = new Chart(ctxPegawai, {
    type: 'bar',
    data: {
      labels: ['Total Kunjungan','Selesai','Sedang Bertamu','Ditolak'],
      datasets: [{
        label: 'Statistik {{ $bulanList[$bulan] }} {{ $tahun }}',
        data: [
          {{ $totalKunjungan }},
          {{ $selesai }},
          {{ $sedangBertamu }},
          {{ $ditolakPegawai }}
        ],
        backgroundColor: ['#4e73df','#1cc88a','#f6c23e','#e74a3b']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: true } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
</script>
@endif

@if($role === 'tamu')
<script>
  var ctxTamu = document.getElementById('chartTamu').getContext('2d');
  var chartTamu = new Chart(ctxTamu, {
    type: 'bar',
    data: {
      labels: ['Total Kunjungan Saya','Diterima','Ditolak','Undangan Rapat'],
      datasets: [{
        label: 'Statistik {{ $bulanList[$bulan] }} {{ $tahun }}',
        data: [
          {{ $total }},
          {{ $diterima }},
          {{ $ditolak }},
          {{ $undanganRapat }}
        ],
        backgroundColor: ['#4e73df','#1cc88a','#e74a3b','#f6c23e']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: true } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
</script>
@endif
@endpush
