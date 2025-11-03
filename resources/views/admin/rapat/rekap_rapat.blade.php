@extends('layouts.admin')

@section('title','Rekap Rapat')
@section('page-title','Rekap Rapat')

@section('content')
<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4>Rekap Rapat</h4>
    {{-- Tombol Export PDF --}}
    <a href="{{ route('admin.rapat.rekap.pdf', request()->all()) }}"
       class="btn btn-danger btn-sm">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
  <div class="card-body">
    {{-- Form Filter --}}
    <form method="GET" action="{{ route('admin.rapat.rekap') }}" class="mb-3">
      <div class="form-row">
        <div class="col-md-3">
          <label for="start_date">Tanggal Mulai</label>
          <input type="date" name="start_date" id="start_date"
                 value="{{ request('start_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
          <label for="end_date">Tanggal Selesai</label>
          <input type="date" name="end_date" id="end_date"
                 value="{{ request('end_date') }}" class="form-control">
        </div>
        <div class="col-md-3">
          <label for="status">Status Rapat</label>
          <select name="status" id="status" class="form-control">
            <option value="">Semua</option>
            <option value="berjalan" {{ request('status')=='berjalan' ? 'selected' : '' }}>Berjalan</option>
            <option value="selesai" {{ request('status')=='selesai' ? 'selected' : '' }}>Selesai</option>
            <option value="dibatalkan" {{ request('status')=='dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
          </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-primary mr-2">
            <i class="fas fa-filter"></i> Filter
          </button>
          <a href="{{ route('admin.rapat.rekap') }}" class="btn btn-secondary">
            Reset
          </a>
        </div>
      </div>
    </form>

    {{-- Tabel Rekap --}}
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Judul Rapat</th>
          <th>Waktu</th>
          <th>Lokasi</th>
          <th>Status</th>
          <th>Total Undangan</th>
          <th>Hadir</th>
          <th>Selesai</th> {{-- ✅ kolom baru --}}
          <th>Tidak Hadir</th>
          <th>Pending</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rekap as $r)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $r['judul'] }}</td>
          <td>{{ $r['waktu'] }}</td>
          <td>{{ $r['lokasi'] }}</td>
          <td>{{ ucfirst($r['status']) }}</td>
          <td>{{ $r['total'] }}</td>
          <td><span class="badge badge-success">{{ $r['hadir'] }}</span></td>
          <td><span class="badge badge-secondary">{{ $r['selesai'] ?? 0 }}</span></td> {{-- ✅ --}}
          <td><span class="badge badge-danger">{{ $r['tidak'] }}</span></td>
          <td><span class="badge badge-warning text-dark">{{ $r['pending'] }}</span></td>
        </tr>
        @empty
        <tr><td colspan="10" class="text-center">Tidak ada data rapat</td></tr>
        @endforelse
      </tbody>
    </table>
    </div>

    {{-- Ringkasan total semua rapat --}}
    @if($rekap->count() > 0)
    <div class="row text-center mt-4">
      <div class="col-md-2 mb-2">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="text-muted">Total Rapat</h6>
            <h4 class="mb-0 text-dark">{{ $rekap->count() }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-2 mb-2">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="text-muted">Total Undangan</h6>
            <h4 class="mb-0 text-dark">{{ $rekap->sum('total') }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-2 mb-2">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="text-success">Total Hadir</h6>
            <h4 class="text-success mb-0">{{ $rekap->sum('hadir') }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-2 mb-2">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="text-secondary">Total Selesai</h6>
            <h4 class="text-secondary mb-0">{{ $rekap->sum('selesai') }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-2 mb-2">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="text-danger">Total Tidak Hadir</h6>
            <h4 class="text-danger mb-0">{{ $rekap->sum('tidak') }}</h4>
          </div>
        </div>
      </div>
      <div class="col-md-2 mb-2">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="text-warning">Total Pending</h6>
            <h4 class="text-warning mb-0">{{ $rekap->sum('pending') }}</h4>
          </div>
        </div>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection
