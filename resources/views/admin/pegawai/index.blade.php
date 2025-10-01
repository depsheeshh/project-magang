@extends('layouts.admin')
@section('title','Data Pegawai')
@section('page-title','Data Pegawai')

@section('content')
<div class="card">
  <div class="card-header">
    <h4>Manajemen Pegawai</h4>
  </div>
  <div class="card-body">
    <p class="mb-2">Kelola data pegawai. Klik tombol di bawah untuk menambah pegawai baru.</p>
    <button class="btn btn-primary" data-toggle="modal" data-target="#createPegawaiModal">
      Tambah Pegawai
    </button>
  </div>
</div>

<div class="card mt-3">
  <div class="card-body">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Nama</th>
          <th>Jabatan</th>
          <th>Bidang</th>
          <th>Email</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pegawai as $p)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $p->user->name }}</td>
          <td>{{ $p->jabatan->nama_jabatan ?? '-' }}</td>
          <td>{{ $p->bidang->nama_bidang ?? '-' }}</td>
          <td>{{ $p->user->email }}</td>
          <td>
            <a href="{{ route('admin.pegawai.show',$p->id) }}"
                class="btn btn-info btn-sm" title="Lihat Detail">
                <i class="fas fa-eye"></i>
            </a>
            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPegawaiModal{{ $p->id }}">
                <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('admin.pegawai.destroy',$p->id) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <input type="hidden" name="reason" value="Menghapus pegawai {{ $p->user->name }}">
                <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">
                <i class="fas fa-trash"></i>
                </button>
            </form>
            </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    {{ $pegawai->links() }}
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Create -->
<div class="modal fade" id="createPegawaiModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.pegawai.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" required>
              @foreach($users as $u)
                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" class="form-control">
          </div>
          <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="telepon" class="form-control">
          </div>
          <div class="form-group">
            <label>Bidang</label>
            <select name="bidang_id" class="form-control">
              @foreach($bidang as $b)
                <option value="{{ $b->id }}">{{ $b->nama_bidang }}</option>
              @endforeach
            </select>
          </div>
                    <div class="form-group">
            <label>Jabatan</label>
            <select name="jabatan_id" class="form-control">
              @foreach($jabatan as $j)
                <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Alasan</label>
            <textarea name="reason" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit -->
@foreach($pegawai as $p)
<div class="modal fade" id="editPegawaiModal{{ $p->id }}" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('admin.pegawai.update',$p->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Edit Pegawai</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>User</label>
            <select name="user_id" class="form-control" disabled>
              <option value="{{ $p->user->id }}">{{ $p->user->name }} ({{ $p->user->email }})</option>
            </select>
          </div>
          <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" value="{{ $p->nip }}" class="form-control">
          </div>
          <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="telepon" value="{{ $p->telepon }}" class="form-control">
          </div>
          <div class="form-group">
            <label>Bidang</label>
            <select name="bidang_id" class="form-control">
              @foreach($bidang as $b)
                <option value="{{ $b->id }}" {{ $p->bidang_id == $b->id ? 'selected' : '' }}>
                  {{ $b->nama_bidang }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Jabatan</label>
            <select name="jabatan_id" class="form-control">
              @foreach($jabatan as $j)
                <option value="{{ $j->id }}" {{ $p->jabatan_id == $j->id ? 'selected' : '' }}>
                  {{ $j->nama_jabatan }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Alasan</label>
            <textarea name="reason" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach
@endsection
