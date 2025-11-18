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
      {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(route('survey.rapat.form', $survey_rapat->slug)) !!}
      <p class="text-muted mt-2">Scan QR ini untuk mengisi survey rapat.</p>
    </div>

    {{-- 🔥 Info rapat yang menggunakan survey ini --}}
    <div class="mb-4">
      <h5 class="mb-2"><i class="fas fa-handshake"></i> Dipakai di Rapat:</h5>
      @if($survey_rapat->rapat->isNotEmpty())
        <ul class="list-group">
          @foreach($survey_rapat->rapat as $r)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <div>
                <strong class="text-dark">{{ $r->judul }}</strong>
                <span class="badge badge-primary text-uppercase">{{ ucfirst($r->jenis_rapat) }}</span><br>
                <small class="text-muted">
                  {{ \Carbon\Carbon::parse($r->waktu_mulai)->format('d/m/Y H:i') }} -
                  {{ \Carbon\Carbon::parse($r->waktu_selesai)->format('d/m/Y H:i') }}
                </small>
              </div>
              <a href="{{ route('admin.rapat.show', $r->id) }}" class="btn btn-sm btn-outline-info">
                <i class="fas fa-eye"></i> Detail Rapat
              </a>
            </li>
          @endforeach
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
            @if($survey_rapat->tipe === 'eksternal')
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
            @if($survey_rapat->tipe === 'eksternal')
            <td>{{ $r->instansi ?? '-' }}</td>
            @endif
            <td>{{ $r->created_at->format('d M Y H:i') }}</td>
            <td class="text-center">
            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#responModal{{ $r->id }}">
                <i class="fas fa-eye"></i> Lihat Jawaban
            </button>
            </td>
        </tr>

        @section('modals')
        <!-- Modal Detail Jawaban -->
        <div class="modal fade" id="responModal{{ $r->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Detail Jawaban - {{ $r->nama }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                @if(is_array($r->jawaban))
                    <ul class="list-group">
                    @foreach($r->jawaban as $pertanyaan => $jawab)
                        <li class="list-group-item">
                        <strong>{{ $pertanyaan }}</strong><br>
                        @if(is_array($jawab))
                            {{ implode(', ', $jawab) }}
                        @else
                            {{ $jawab }}
                        @endif
                        </li>
                    @endforeach
                    </ul>
                @else
                    <p>{{ $r->jawaban ?? '-' }}</p>
                @endif
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
            </div>
        </div>
        @endsection
        @empty
        <tr>
            <td colspan="{{ $survey_rapat->tipe === 'eksternal' ? 5 : 4 }}" class="text-center text-muted">
            Belum ada responden
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
    </div>

  </div>
</div>
@endsection
