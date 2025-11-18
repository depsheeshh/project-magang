@extends('layouts.admin')

@section('title', 'Survey Rapat')
@section('page-title', 'Survey Rapat')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Daftar Survey Rapat</h4>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createSurveyModal">
      <i class="fas fa-plus-circle"></i> Tambah Survey Rapat
    </button>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="thead-dark">
          <tr>
            <th>#</th>
            <th>Judul</th>
            <th>Tipe</th>
            <th>Responden</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($surveys as $s)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $s->judul }}</td>
            <td>
              @if($s->tipe === 'internal')
                <span class="badge badge-info">Internal</span>
              @else
                <span class="badge badge-success">Eksternal</span>
              @endif
            </td>
            <td>
              @if($s->tipe === 'internal')
                {{-- daftar pegawai --}}
                <ul class="list-unstyled mb-0">
                  @forelse($s->respon as $r)
                    <li><i class="fas fa-user"></i> {{ $r->nama }}</li>
                  @empty
                    <li class="text-muted">Belum ada pegawai isi</li>
                  @endforelse
                </ul>
              @else
                {{-- daftar tamu eksternal --}}
                <ul class="list-unstyled mb-0">
                  @forelse($s->respon as $r)
                    <li><i class="fas fa-user"></i> {{ $r->nama }} <small class="text-muted">({{ $r->instansi ?? '-' }})</small></li>
                  @empty
                    <li class="text-muted">Belum ada tamu isi</li>
                  @endforelse
                </ul>
              @endif
            </td>
            <td>
                <a href="{{ route('admin.survey-rapat.show', $s->id) }}" class="btn btn-info btn-sm">
                    <i class="fas fa-eye"></i>
                </a>
              <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editSurveyModal{{ $s->id }}">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route('admin.survey-rapat.destroy', $s->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted">Belum ada survey rapat</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $surveys->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Tambah Survey -->
<div class="modal fade" id="createSurveyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.survey-rapat.store') }}">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Tambah Survey Rapat</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="form-group mt-2">
            <label>Tipe</label>
            <select name="tipe" class="form-control" required>
            <option value="internal">Internal</option>
            <option value="eksternal">Eksternal</option>
            </select>
        </div>
        <div class="form-group mt-2">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control"></textarea>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Survey -->
@foreach($surveys as $s)
<div class="modal fade" id="editSurveyModal{{ $s->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.survey-rapat.update', $s->id) }}">
        @csrf @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Survey Rapat</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Judul</label>
            <input type="text" name="judul" class="form-control" value="{{ $s->judul }}" required>
          </div>
          <div class="form-group mt-2">
            <label>Tipe</label>
            <select name="tipe" class="form-control" required>
              <option value="internal" {{ $s->tipe=='internal'?'selected':'' }}>Internal</option>
              <option value="eksternal" {{ $s->tipe=='eksternal'?'selected':'' }}>Eksternal</option>
            </select>
          </div>
          <div class="form-group mt-2">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ $s->deskripsi }}</textarea>
          </div>

          {{-- Preview QR Code --}}
          <div class="mt-3 text-center">
            <label>QR Code Akses</label><br>
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate(route('survey.rapat.form', $s->slug)) !!}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
