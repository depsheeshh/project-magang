@php
  $prefix = Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
@endphp

@extends('layouts.admin')

@section('title','Detail Tamu Instansi')
@section('page-title','Detail Tamu - '.$undanganInstansi->instansi->nama_instansi)

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Daftar Tamu dari {{ $undanganInstansi->instansi->nama_instansi }}</h4>
    <p class="mb-0">Kuota: {{ max(0, $undanganInstansi->kuota - $undanganInstansi->jumlah_hadir) }} | Hadir: {{ $undanganInstansi->jumlah_hadir }}</p>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Jabatan</th>
            <th>Status Kehadiran</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tamuList as $tamu)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $tamu->user->name ?? '-' }}</td>
              <td>{{ $tamu->user->email ?? '-' }}</td>
              <td>{{ $tamu->jabatan ?? '-' }}</td>
              <td>{{ ucfirst($tamu->status_kehadiran) }}</td>
              <td>{{ $tamu->checked_in_at ?? '-' }}</td>
              <td>{{ $tamu->checked_out_at ?? '-' }}</td>
              <td>
                <form action="{{ route($prefix.'.rapat.destroyTamuInstansi', [$rapat->id, $undanganInstansi->id, $tamu->id]) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus tamu ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-center">Belum ada tamu dari instansi ini</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<a href="{{ route($prefix.'.rapat.show', $rapat->id) }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection
