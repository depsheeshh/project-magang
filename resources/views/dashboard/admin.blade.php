@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Dashboard untuk Admin --}}
@if($role === 'admin')
<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary">
        <i class="fas fa-users"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total User</h4></div>
        <div class="card-body">{{ $totalUsers }}</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success">
        <i class="fas fa-id-card"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Pegawai</h4></div>
        <div class="card-body">{{ $totalPegawai }}</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning">
        <i class="fas fa-sitemap"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Bidang</h4></div>
        <div class="card-body">{{ $totalBidang }}</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-danger">
        <i class="fas fa-briefcase"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Jabatan</h4></div>
        <div class="card-body">{{ $totalJabatan }}</div>
      </div>
    </div>
  </div>
</div>
@endif

{{-- Dashboard untuk Frontliner --}}
@if($role === 'frontliner')
<div class="card">
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
                {{-- aman terhadap null: jika tidak ada tamu, tampilkan '-' --}}
                <td>{{ $k->tamu->nama ?? $k->tamu->user->name ?? '-' }}</td>

                {{-- bidang lewat relasi pegawai -> bidang --}}
                <td>{{ $k->pegawai->bidang->nama_bidang ?? '-' }}</td>

                {{-- nama pegawai dari relasi user --}}
                <td>{{ $k->pegawai->user->name ?? '-' }}</td>

                <td>{{ $k->keperluan }}</td>

                {{-- waktu_masuk aman: gunakan Carbon parse (atau casts di model) --}}
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
<div class="card">
  <div class="card-header"><h4>Kunjungan Terbaru</h4></div>
  <div class="card-body">
    @if($kunjunganTerbaru->isEmpty())
      <div class="alert alert-info">Belum ada kunjungan terbaru.</div>
    @else
      <ul class="list-group">
        @foreach($kunjunganTerbaru as $k)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            {{ $k->tamu->nama }} - {{ $k->keperluan }}
            <span class="badge badge-secondary">{{ $k->status }}</span>
          </li>
        @endforeach
      </ul>
    @endif
  </div>
</div>
@endif

{{-- Dashboard untuk Tamu --}}
@if($role === 'tamu')
<div class="row">
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary"><i class="fas fa-users"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Total Kunjungan Saya</h4></div>
        <div class="card-body">{{ $total }}</div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success"><i class="fas fa-check"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Kunjungan Diterima</h4></div>
        <div class="card-body">{{ $diterima }}</div>
      </div>
    </div>
  </div>
  <div class="col-lg-4 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-danger"><i class="fas fa-times"></i></div>
      <div class="card-wrap">
        <div class="card-header"><h4>Kunjungan Ditolak</h4></div>
        <div class="card-body">{{ $ditolak }}</div>
      </div>
    </div>
  </div>
</div>
@endif

@endsection
