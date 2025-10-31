@extends('layouts.admin')
@section('title','Data Link Survey SKM')
@section('page-title','Data Link Survey SKM')

@section('content')
<div class="container-fluid">
  <h4 class="mb-3">🔗 Daftar Link Survey SKM</h4>

  {{-- Form tambah link survey --}}
  <div class="card mb-3">
    <div class="card-body">
      <form action="{{ route('admin.survey_links.store') }}" method="POST" class="row g-2">
        @csrf
        <div class="col-md-8">
          <input type="url" name="link_survey" class="form-control" placeholder="Masukkan link survey" required>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-primary">Tambah Link</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Tabel daftar link survey --}}
  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Link Survey</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($surveys as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td><a href="{{ $s->link_survey }}" target="_blank">{{ $s->link_survey }}</a></td>
            <td>
              @if($s->is_active)
                <span class="badge bg-success">Active</span>
              @else
                <span class="badge bg-secondary">Inactive</span>
              @endif
            </td>
            <td>
              @if($s->is_active)
                <form action="{{ route('admin.survey_links.deactivate',$s->id) }}" method="POST" style="display:inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-sm btn-danger">Nonaktifkan</button>
                </form>
              @else
                <form action="{{ route('admin.survey_links.activate',$s->id) }}" method="POST" style="display:inline">
                  @csrf @method('PATCH')
                  <button type="submit" class="btn btn-sm btn-success">Aktifkan</button>
                </form>
              @endif

              <form action="{{ route('admin.survey_links.destroy',$s->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin hapus link ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="text-center">Belum ada link survey</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
