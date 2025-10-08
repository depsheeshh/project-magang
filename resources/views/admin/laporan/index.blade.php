@extends('layouts.admin')

@section('title','Laporan Kunjungan')
@section('page-title','Laporan Kunjungan')

@section('content')

@push('style')
    <style>
        .card-stat {
        display: flex;
        align-items: center;
        padding: 15px 20px; /* lebih lega */
        }
        .card.shadow-sm {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        }


        .card-stat .icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-right: 15px;
        color: #fff;
        }

        .card-stat .info {
        flex: 1;
        }

        .card-stat .info .label {
        font-weight: bold;
        font-size: 14px;
        margin-bottom: 4px;
        line-height: 1.2;
        }

        .card-stat .info .value {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
        line-height: 1.2;
        }
    </style>
@endpush

<div class="card">
  <div class="card-header"><h4>Rekap Kunjungan</h4></div>
  <div class="card-body">

    {{-- Form Filter Periode + Status --}}
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="mb-3">
      <div class="row">
        {{-- Kolom kiri: input tanggal + status --}}
        <div class="col-md-6 d-flex align-items-center flex-wrap">
          <div class="form-group mr-2">
            <label for="start_date" class="mr-2">Dari</label>
            <input type="date" name="start_date" id="start_date"
                   value="{{ $start_date ?? '' }}" class="form-control">
          </div>
          <div class="form-group mr-2">
            <label for="end_date" class="mr-2">Sampai</label>
            <input type="date" name="end_date" id="end_date"
                   value="{{ $end_date ?? '' }}" class="form-control">
          </div>
          <div class="form-group">
            <label for="status" class="mr-2">Status</label>
            <select name="status" id="status" class="form-control">
              <option value="">-- Semua --</option>
              <option value="menunggu" {{ ($status ?? '')==='menunggu' ? 'selected' : '' }}>Menunggu</option>
              <option value="sedang_bertamu" {{ ($status ?? '')==='sedang_bertamu' ? 'selected' : '' }}>Sedang Bertamu</option>
              <option value="selesai" {{ ($status ?? '')==='selesai' ? 'selected' : '' }}>Selesai</option>
              <option value="ditolak" {{ ($status ?? '')==='ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
          </div>
        </div>


        {{-- Kolom kanan: tombol --}}
        <div class="col-md-6 d-flex justify-content-end align-items-center">
            <button type="submit" class="btn btn-primary mr-2">Cari</button>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-secondary mr-2">Reset</a>
            <a href="{{ route('admin.laporan.cetak', request()->all()) }}" target="_blank" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a>

        </div>
    </form>

    {{-- Rekap Statistik --}}
    <div class="row mb-3">
        {{-- Total --}}
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card shadow-sm">
            <div class="card-stat">
                <div class="icon bg-primary"><i class="fas fa-list"></i></div>
                <div class="info">
                <div class="label">Total</div>
                <div class="value">{{ $rekap['total'] }}</div>
                </div>
            </div>
            </div>
        </div>

        {{-- Menunggu --}}
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card shadow-sm">
            <div class="card-stat">
                <div class="icon bg-warning"><i class="fas fa-hourglass-half"></i></div>
                <div class="info">
                <div class="label">Menunggu</div>
                <div class="value">{{ $rekap['menunggu'] }}</div>
                </div>
            </div>
            </div>
        </div>

        {{-- Sedang Bertamu --}}
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card shadow-sm">
            <div class="card-stat">
                <div class="icon bg-info"><i class="fas fa-user-clock"></i></div>
                <div class="info">
                <div class="label">Sedang Bertamu</div>
                <div class="value">{{ $rekap['sedang_bertamu'] }}</div>
                </div>
            </div>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card shadow-sm">
            <div class="card-stat">
                <div class="icon bg-success"><i class="fas fa-check"></i></div>
                <div class="info">
                <div class="label">Selesai</div>
                <div class="value">{{ $rekap['selesai'] }}</div>
                </div>
            </div>
            </div>
        </div>

        {{-- Ditolak --}}
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card shadow-sm">
            <div class="card-stat">
                <div class="icon bg-danger"><i class="fas fa-times"></i></div>
                <div class="info">
                <div class="label">Ditolak</div>
                <div class="value">{{ $rekap['ditolak'] }}</div>
                </div>
            </div>
            </div>
        </div>
        </div>



    {{-- Tabel Laporan --}}
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Nama Tamu</th>
          <th>Pegawai Tujuan</th>
          <th>Keperluan</th>
          <th>Status</th>
          <th>Waktu Masuk</th>
          <th>Waktu Keluar</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kunjungan as $k)
          <tr>
            <td>{{ $k->tamu?->nama ?? $k->tamu?->user?->name ?? '-' }}</td>
            <td>{{ $k->pegawai?->user?->name ?? '-' }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>{{ ucfirst($k->status) }}</td>
            <td>{{ $k->waktu_masuk }}</td>
            <td>{{ $k->waktu_keluar ?? '-' }}</td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center">Belum ada data kunjungan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
