@extends('layouts.admin')

@section('title','Detail Survey')
@section('page-title','Detail Survey Tamu')

@section('content')
<div class="card shadow-sm border-0 rounded-4">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i> Detail Survey Tamu</h4>
    <a href="{{ route('admin.surveys.index') }}" class="btn btn-light btn-sm text-primary shadow-sm">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <tbody>
          <tr>
            <th style="width: 25%;">Nama Tamu</th>
            <td>{{ $survey->user->name }}</td>
          </tr>
          <tr>
            <th>Instansi</th>
            <td>{{ $survey->kunjungan->tamu->instansi ?? '-' }}</td>
          </tr>
          <tr>
            <th>Pegawai Tujuan</th>
            <td>{{ $survey->kunjungan->pegawai?->user?->name ?? '-' }}</td>
          </tr>
          <tr>
            <th>Status Survey</th>
            <td>
              @if(!is_null($survey->rating))
                <span class="badge bg-success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Sudah diisi</span>
              @else
                <span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-hourglass-half me-1"></i> Belum diisi</span>
              @endif
            </td>
          </tr>

          {{-- ⭐ Kemudahan Registrasi --}}
          <tr>
            <th>Kemudahan Registrasi</th>
            <td>
              @for($i=1; $i<=5; $i++)
                <i class="fas fa-star {{ $i <= ($survey->kemudahan_registrasi ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
              @endfor
            </td>
          </tr>

          {{-- ⭐ Keramahan Pelayanan --}}
          <tr>
            <th>Keramahan Pelayanan</th>
            <td>
              @for($i=1; $i<=5; $i++)
                <i class="fas fa-star {{ $i <= ($survey->keramahan_pelayanan ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
              @endfor
            </td>
          </tr>

          {{-- ⭐ Waktu Tunggu --}}
          <tr>
            <th>Waktu Tunggu</th>
            <td>
              @for($i=1; $i<=5; $i++)
                <i class="fas fa-star {{ $i <= ($survey->waktu_tunggu ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
              @endfor
            </td>
          </tr>

          {{-- ⭐ Rating Umum --}}
          <tr>
            <th>Rating Umum</th>
            <td>
              @for($i=1; $i<=5; $i++)
                <i class="fas fa-star {{ $i <= ($survey->rating ?? 0) ? 'text-warning' : 'text-muted' }}"></i>
              @endfor
            </td>
          </tr>

          <tr>
            <th>Saran / Masukan</th>
            <td>{{ $survey->saran ?? '-' }}</td>
          </tr>

          <tr>
            <th>Tanggal Survey</th>
            <td>{{ $survey->created_at->format('d-m-Y H:i') }}</td>
          </tr>

          @if($survey->link)
          <tr>
            <th>Link Survey</th>
            <td>
              <div class="input-group input-group-sm" style="max-width: 400px;">
                <input type="text" class="form-control" value="{{ $survey->link }}" readonly id="link-{{ $survey->id }}">
                <button type="button" class="btn btn-outline-primary" onclick="copyLink({{ $survey->id }})">
                  <i class="fas fa-copy"></i>
                </button>
              </div>
            </td>
          </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  th {
    width: 25%;
    font-weight: 600;
    color: #374151;
    background: #f9fafb;
  }
  td {
    color: #1f2937;
  }
  .fa-star {
    font-size: 1.1rem;
    margin-right: 3px;
    transition: transform 0.2s;
  }
  .fa-star.text-warning {
    color: #facc15 !important; /* gold */
  }
  .fa-star.text-muted {
    color: #d1d5db !important;
  }
  .fa-star:hover {
    transform: scale(1.2);
  }
</style>
@endpush

@push('scripts')
<script>
function copyLink(id) {
  const input = document.getElementById('link-' + id);
  const text = input.value;

  if (navigator.clipboard) {
    navigator.clipboard.writeText(text)
      .then(() => toastr.success("Link survey berhasil disalin!"))
      .catch(() => toastr.error("Gagal menyalin link."));
  } else {
    input.select();
    document.execCommand("copy");
    toastr.success("Link survey berhasil disalin!");
  }
}
</script>
@endpush
