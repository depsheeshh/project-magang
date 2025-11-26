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

  {{-- Total Kunjungan Tamu --}}
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
        <div class="card-icon bg-warning"><i class="fas fa-user-clock"></i></div>
        <div class="card-wrap">
        <div class="card-header"><h4>Total Kunjungan Tamu</h4></div>
        <div class="card-body">{{ $totalKunjunganTamu }}</div>
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

  {{-- Survey Rapat Terisi --}}
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
        <div class="card-icon bg-success"><i class="fas fa-check-circle"></i></div>
        <div class="card-wrap">
        <div class="card-header"><h4>Survey Rapat Terisi</h4></div>
        <div class="card-body">{{ $surveyRapatFilled }}</div>
        </div>
    </div>
    </div>


</div>
<div class="row mt-5">
<div class="col-12">
    <div class="card shadow-sm">
    <div class="card-header"><h4 class="mb-0">📊 Grafik Statistik Rapat & Kunjungan</h4></div>
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
<div class="row mt-5">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header"><h4 class="mb-0">📊 Statistik Kunjungan Frontliner</h4></div>
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
      <div class="card-header"><h4 class="mb-0">📊 Statistik Kunjungan Pegawai</h4></div>
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
<div class="row mt-5">
  <div class="col-12">
    <div class="card shadow-sm">
      <div class="card-header"><h4 class="mb-0">📊 Statistik Kunjungan Tamu</h4></div>
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
<script>
  var ctx = document.getElementById('chartAdmin').getContext('2d');
  var chartAdmin = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
        'Total Rapat',
        'Survey Terisi',
        'Survey Belum Terisi',
        'Total Tamu',
        'Total Kunjungan Tamu'
        ],
        datasets: [{
        label: 'Statistik',
        data: [
            {{ $totalRapat }},
            {{ $surveyRapatFilled }},
            {{ $surveyRapatPending }},
            {{ $totalTamu }},
            {{ $totalKunjunganTamu }}
        ],
        backgroundColor: [
            '#4e73df',
            '#1cc88a',
            '#e74a3b',
            '#36b9cc',
            '#f6c23e'
        ]
        }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
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
<script>
  var ctxFront = document.getElementById('chartFrontliner').getContext('2d');
  var chartFrontliner = new Chart(ctxFront, {
    type: 'bar',
    data: {
      labels: ['Total Kunjungan','Diterima','Ditolak','Sedang Bertamu','Selesai'],
      datasets: [{
        label: 'Statistik',
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
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
</script>
<script>
  var ctxPegawai = document.getElementById('chartPegawai').getContext('2d');
  var chartPegawai = new Chart(ctxPegawai, {
    type: 'bar',
    data: {
      labels: ['Total Kunjungan','Selesai','Sedang Bertamu','Ditolak'],
      datasets: [{
        label: 'Statistik',
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
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
</script>
<script>
  var ctxTamu = document.getElementById('chartTamu').getContext('2d');
  var chartTamu = new Chart(ctxTamu, {
    type: 'bar',
    data: {
      labels: ['Total Kunjungan Saya','Diterima','Ditolak','Undangan Rapat'],
      datasets: [{
        label: 'Statistik',
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
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
  });
</script>
@endpush

