@extends('layouts.admin')

@section('title', 'Detail Survey Rapat')
@section('page-title', 'Detail Survey Rapat')

@section('content')
<style>
    body.dark-mode .modal-content {
        color: #000000;
        background-color: #ffffff;
    }
</style>

<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">
      <i class="fas fa-poll"></i> {{ $survey_rapat->judul }}
      <span class="badge badge-info">{{ ucfirst($survey_rapat->tipe) }}</span>
    </h4>
    <a href="{{ route('admin.survey-rapat.index') }}" class="btn btn-secondary btn-sm">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>
  <div class="card-body">
    <div class="mb-3">
      <strong>Deskripsi:</strong><br>
      {{ $survey_rapat->deskripsi ?? '-' }}
    </div>

    <div class="mb-4 text-center">
      <strong>QR Code Akses Survey:</strong><br>
      @if($survey_rapat->tipe === 'Internal')
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(route('pegawai.survey.rapat.form.internal', $survey_rapat->slug)) !!}
        <p class="text-muted mt-2">Scan QR ini untuk mengisi survey rapat internal.</p>
        <p class="mt-2">
            <a href="{{ route('pegawai.survey.rapat.form.internal', $survey_rapat->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-link"></i> Buka Survey Internal
            </a>
        </p>
        @else
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(route('tamu.survey.rapat.form.eksternal', $survey_rapat->slug)) !!}
        <p class="text-muted mt-2">Scan QR ini untuk mengisi survey rapat eksternal.</p>
        <p class="mt-2">
            <a href="{{ route('tamu.survey.rapat.form.eksternal', $survey_rapat->slug) }}" target="_blank" class="btn btn-sm btn-outline-success">
            <i class="fas fa-link"></i> Buka Survey Eksternal
            </a>
        </p>
        @endif
    </div>

    {{-- 🔥 Info rapat yang menggunakan survey ini --}}
    <div class="mb-4">
      <h5 class="mb-2"><i class="fas fa-handshake"></i> Dipakai di Rapat:</h5>
      @if($survey_rapat->rapat)
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong class="text-dark">{{ $survey_rapat->rapat->judul }}</strong>
                <span class="badge badge-primary text-uppercase">{{ ucfirst($survey_rapat->rapat->jenis_rapat) }}</span><br>
                <small class="text-muted">
                {{ \Carbon\Carbon::parse($survey_rapat->rapat->waktu_mulai)->format('d/m/Y H:i') }} -
                {{ \Carbon\Carbon::parse($survey_rapat->rapat->waktu_selesai)->format('d/m/Y H:i') }}
                </small>
            </div>
            <a href="{{ route('admin.rapat.show', $survey_rapat->rapat->id) }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-eye"></i> Detail Rapat
            </a>
            </li>
        </ul>
        @else
        <p class="text-muted">Survey ini belum dipakai di rapat manapun.</p>
        @endif
    </div>

    <h5 class="mb-3"><i class="fas fa-users"></i> Responden:</h5>
    <div class="table-responsive mb-4">
      <table class="table table-bordered table-hover align-middle">
        <thead class="thead-dark">
          <tr class="text-center">
            <th>#</th>
            <th>Nama</th>
            @if($survey_rapat->tipe === 'Eksternal')
              <th>Instansi</th>
            @endif
            <th>Waktu Isi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($survey_rapat->respon as $r)
          <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $r->nama }}</td>
            @if($survey_rapat->tipe === 'Eksternal')
              <td>{{ $r->instansi ?? '-' }}</td>
            @endif
            <td>{{ $r->created_at->format('d M Y H:i') }}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#responModal{{ $r->id }}">
                <i class="fas fa-eye"></i> Lihat Jawaban
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="{{ $survey_rapat->tipe === 'Eksternal' ? 5 : 4 }}" class="text-center text-muted">
              Belum ada responden
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- 🔥 Render semua modal di luar loop --}}
@section('modals')
@foreach($survey_rapat->respon as $r)
<div class="modal fade" id="responModal{{ $r->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Detail Jawaban - {{ $r->nama }}</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        @foreach($r->jawaban ?? [] as $pertanyaan => $jawab)
          <p><strong>{{ $pertanyaan }}</strong><br>
          {{ is_array($jawab) ? implode(', ', $jawab) : $jawab }}</p>
        @endforeach
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endforeach
@endsection
@endsection
