@extends('layouts.admin')

@section('title','Detail Survey')
@section('page-title','Detail Survey Tamu')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Detail Survey</h4>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th>Nama Tamu</th>
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
          @if(!is_null($survey->rating) || !is_null($survey->feedback))
            <span class="badge bg-success">Sudah diisi</span>
          @else
            <span class="badge bg-warning text-dark">Belum diisi</span>
          @endif
        </td>
      </tr>
      <tr>
        <th>Rating</th>
        <td>
          @for($i=1; $i<=5; $i++)
            <i class="fas fa-star {{ $i <= $survey->rating ? 'text-warning' : 'text-secondary' }}"></i>
          @endfor
        </td>
      </tr>
      <tr>
        <th>Feedback</th>
        <td>{{ $survey->feedback ?? '-' }}</td>
      </tr>
      <tr>
        <th>Tanggal Survey</th>
        <td>{{ $survey->created_at->format('d-m-Y H:i') }}</td>
      </tr>
      @if($survey->link)
      <tr>
        <th>Link Survey</th>
        <td>
          <div class="input-group input-group-sm">
            <input type="text" class="form-control" value="{{ $survey->link }}" readonly id="link-{{ $survey->id }}">
            <button type="button" class="btn btn-outline-primary" onclick="copyLink({{ $survey->id }})" title="Salin link">
              <i class="fas fa-copy"></i>
            </button>
          </div>
        </td>
      </tr>
      @endif
    </table>

    <a href="{{ route('admin.surveys.index') }}" class="btn btn-secondary">
      <i class="fas fa-arrow-left"></i> Kembali
    </a>
  </div>
</div>
@endsection

@push('scripts')
<script>
function copyLink(id) {
    const input = document.getElementById('link-' + id);
    const text = input.value;
    navigator.clipboard.writeText(text).then(() => {
        toastr.success("Link survey berhasil disalin!");
    }).catch(() => {
        toastr.error("Gagal menyalin link.");
    });
}
</script>
@endpush
