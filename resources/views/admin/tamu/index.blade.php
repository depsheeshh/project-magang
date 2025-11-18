@extends('layouts.admin')

@section('title', 'Data Tamu')
@section('page-title', 'Data Tamu')

@section('content')
<div class="card shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Daftar Tamu</h4>
    {{-- Admin tidak bisa tambah tamu --}}
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="thead-dark">
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Instansi</th>
              <th>No HP</th>
              <th>Alamat</th>
              <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tamuList as $t)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $t->name }}</td>
                <td>{{ $t->email }}</td>
                <td>{{ optional($t->tamu)->instansi ?? '-' }}</td>
                <td>{{ optional($t->tamu)->no_hp ?? '-' }}</td>
                <td>{{ optional($t->tamu)->alamat ?? '-' }}</td>
                <td>
                  <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editTamuModal{{ $t->id }}">
                    <i class="fas fa-edit"></i>
                  </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted">Belum ada tamu</td></tr>
            @endforelse
        </tbody>
      </table>

      {{ $tamuList->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection

@section('modals')
<!-- Modal Edit -->
@foreach($tamuList as $t)
<div class="modal fade" id="editTamuModal{{ $t->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.tamu.update', $t->id) }}">
        @csrf @method('PUT')
        <div class="modal-header bg-warning text-white">
          <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Tamu</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ $t->name }}" required>
        </div>
        <div class="form-group mt-2">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $t->email }}" readonly>
        </div>

          {{-- Instansi --}}
          <div class="form-group mt-2">
            <label>Instansi</label>
            <select id="instansi_select_{{ $t->id }}" class="form-control">
                <option value="">-- Pilih Instansi --</option>
                @foreach($instansi as $i)
                    <option value="{{ $i->nama_instansi }}"
                        @if(optional($t->tamu)->instansi === $i->nama_instansi) selected @endif>
                        {{ $i->nama_instansi }}
                    </option>
                @endforeach
                <option value="lainnya"
                    @if(!in_array(optional($t->tamu)->instansi, $instansi->pluck('nama_instansi')->toArray())) selected @endif>
                    Lainnya
                </option>
            </select>
            <input type="hidden" name="instansi" id="instansi_hidden_{{ $t->id }}" value="{{ optional($t->tamu)->instansi }}">
          </div>

          <div class="form-group mt-2 d-none" id="instansi_lainnya_wrapper_{{ $t->id }}">
            <label>Instansi Lainnya</label>
            <input type="text" id="instansi_lainnya_{{ $t->id }}" class="form-control" value="{{ !in_array(optional($t->tamu)->instansi, $instansi->pluck('nama_instansi')->toArray()) ? optional($t->tamu)->instansi : '' }}">
          </div>

          <div class="form-group mt-2">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ $t->tamu->no_hp ?? '' }}">
          </div>
          <div class="form-group mt-2">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control">{{ $t->tamu->alamat ?? '' }}</textarea>
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

@push('scripts')
<script>
@foreach($tamuList as $t)
(function(){
  const select = document.getElementById('instansi_select_{{ $t->id }}');
  const wrapper = document.getElementById('instansi_lainnya_wrapper_{{ $t->id }}');
  const inputLainnya = document.getElementById('instansi_lainnya_{{ $t->id }}');
  const hiddenField = document.getElementById('instansi_hidden_{{ $t->id }}');

  function toggleInstansi() {
      if (select.value === 'lainnya') {
          wrapper.classList.remove('d-none');
          inputLainnya.setAttribute('required','required');
          hiddenField.value = inputLainnya.value;
      } else {
          wrapper.classList.add('d-none');
          inputLainnya.removeAttribute('required');
          hiddenField.value = select.value;
      }
  }

  select.addEventListener('change', toggleInstansi);
  if(inputLainnya){
    inputLainnya.addEventListener('input', () => hiddenField.value = inputLainnya.value);
  }
  toggleInstansi();
})();
@endforeach
</script>
@endpush
