@extends('layouts.admin')

@section('title','Hasil Survey')
@section('page-title','Daftar Hasil Survey')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Hasil Survey Tamu</h4>
  </div>
  <div class="card-body">
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
            <th>Tamu</th>
            <th>Instansi</th>
            <th>Tanggal</th>
            <th>Link Survey</th>
            <th>Aksi</th>
        </tr>
        </thead>
      <tbody>
        @forelse($surveys as $s)
            <tr>
            <td>{{ $s->user->name }}</td>
            <td>{{ $s->kunjungan->tamu->instansi ?? '-' }}</td>
            <td>{{ $s->created_at->format('d-m-Y H:i') }}</td>
            <td>
                @if(!is_null($s->rating) || !is_null($s->feedback))
                <span class="badge bg-success">Sudah diisi</span>
                @elseif($s->link)
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" value="{{ $s->link }}" readonly id="link-{{ $s->id }}">
                    <button type="button" class="btn btn-outline-primary" onclick="copyLink({{ $s->id }})">
                    <i class="fas fa-copy"></i>
                    </button>
                </div>
                @else
                <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                <a href="{{ route('admin.surveys.show',$s->id) }}" class="btn btn-sm btn-info">
                <i class="fas fa-eye"></i> Detail
                </a>
                @if(is_null($s->rating))
                    <a href="{{ route('admin.surveys.fill',$s->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit"></i> Isi Survey
                    </a>
                @endif
            </td>
            </tr>
        @empty
            <tr>
            <td colspan="9" class="text-center text-muted">Belum ada survey</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-3">
      {{ $surveys->links() }}
    </div>
  </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function copyLink(id) {
    const input = document.getElementById('link-' + id);
    const text = input.value;

    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            toastr.success("Link survey berhasil disalin!");
        }).catch(() => {
            toastr.error("Gagal menyalin link.");
        });
    } else {
        input.select();
        document.execCommand("copy");
        toastr.success("Link survey berhasil disalin!");
    }
}
</script>
@endpush
