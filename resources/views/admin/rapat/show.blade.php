@php
  $prefix = Auth::user()->hasRole('pegawai') ? 'pegawai' : 'admin';
@endphp


@extends('layouts.admin')

@section('title','Detail Rapat')
@section('page-title','Detail Rapat - ' . $rapat->jenis_rapat)

@section('content')

@if(session('warning'))
  <div class="alert alert-warning alert-dismissible fade show" role="alert">
    {{ session('warning') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
@endif

<div class="card mb-3">
  <div class="card-header">
    <h5>Tambah Undangan</h5>
  </div>
  <div class="card-body">

    @if($rapat->jenis_rapat === 'Internal')
      {{-- Form undangan untuk pegawai DKIS --}}
      <form action="{{ route($prefix.'.rapat.storeInvitation', $rapat->id) }}" method="POST">
        @csrf
        <div class="form-row">
          <div class="col-md-8">
            <select name="user_id" class="form-control" required>
              <option value="">-- Pilih Pegawai DKIS --</option>
              @foreach($users as $user)
                @if($user->hasRole('pegawai'))
                  <option value="{{ $user->id }}">
                    {{ $user->name }}
                  </option>
                @endif
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-user-plus"></i> Tambah
            </button>
          </div>
        </div>
      </form>

      <hr>
      {{-- Bulk invite semua pegawai --}}
      <form action="{{ route($prefix.'.rapat.inviteAll', $rapat->id) }}" method="POST">
        @csrf
        <input type="hidden" name="role" value="pegawai">
        <button type="submit" class="btn btn-sm btn-success">
          <i class="fas fa-user-tie"></i> Tambahkan Semua Pegawai
        </button>
      </form>

      <hr>
    <form action="{{ route($prefix.'.rapat.inviteByJabatan', $rapat->id) }}" method="POST">
    @csrf
    <div class="form-row">
        <div class="col-md-8">
        <select name="jabatan_id" class="form-control" required>
            <option value="">-- Pilih Jabatan --</option>
            @foreach($jabatans as $jabatan)
            <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
            @endforeach
        </select>
        </div>
        <div class="col-md-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-users"></i> Tambah Pegawai per Jabatan
        </button>
        </div>
    </div>
    </form>


    @elseif($rapat->jenis_rapat === 'Eksternal')
        {{-- Form undangan untuk instansi --}}
        <form action="{{ route($prefix.'.rapat.storeInvitationInstansi', $rapat->id) }}" method="POST">
            @csrf
            <div class="form-row">
            <div class="col-md-8">
                <select name="instansi_id" class="form-control" required>
                <option value="">-- Pilih Instansi --</option>
                @foreach($instansi as $i)
                    @if(!$rapat->undanganInstansi->where('instansi_id', $i->id)->count())
                    <option value="{{ $i->id }}">{{ $i->nama_instansi }}</option>
                    @endif
                @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                <i class="fas fa-building"></i> Tambah Instansi
                </button>
            </div>
            </div>
        </form>

        <hr>
        {{-- Bulk invite semua instansi --}}
        <form action="{{ route($prefix.'.rapat.inviteAllInstansi', $rapat->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">
            <i class="fas fa-university"></i> Tambahkan Semua Instansi
            </button>
        </form>
        @endif
  </div>
</div>



{{-- Detail rapat --}}
<div class="card mb-3">
  <div class="card-header">
    <h4>Detail Rapat</h4>
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Judul</dt>
      <dd class="col-sm-9">{{ $rapat->judul }}</dd>

      <dt class="col-sm-3">Waktu</dt>
      <dd class="col-sm-9">
        {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
        s/d
        {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}
      </dd>

      <dt class="col-sm-3">Status</dt>
      <dd class="col-sm-9">
       @if($rapat->status === 'belum_dimulai')
        <span class="badge badge-warning">Belum Dimulai</span>
        @elseif($rapat->status === 'berjalan')
        <span class="badge badge-primary">Sedang Berjalan</span>
        @elseif($rapat->status === 'selesai')
        <span class="badge badge-success">Selesai</span>
        @elseif($rapat->status === 'dibatalkan')
        <span class="badge badge-secondary">Dibatalkan</span>
        @endif
      </dd>

      <dt class="col-sm-3">Jenis Rapat</dt>
      <dd class="col-sm-9">{{ $rapat->jenis_rapat ?? '-' }}</dd>

      <dt class="col-sm-3">Lokasi</dt>
      <dd class="col-sm-9">{{ $rapat->lokasi ?? '-' }}</dd>

      <dt class="col-sm-3">Koordinat</dt>
      <dd class="col-sm-9">
        Lat: {{ $rapat->latitude ?? '-' }},
        Lon: {{ $rapat->longitude ?? '-' }},
        Radius: {{ $rapat->radius ?? '-' }} m
      </dd>


      @php
        // total tamu maksimal yang diizinkan
        $maxTamu = $rapat->jumlah_tamu ?? 0;

        // jumlah pegawai yang sudah check-in
        $jumlahCheckin = $rapat->undangan->whereNotNull('checked_in_at')->count();

        // sisa kuota = max tamu - sudah checkin
        $sisaKuota = max($maxTamu - $jumlahCheckin, 0);
    @endphp

    <dt class="col-sm-3">Jumlah Tamu</dt>
    <dd class="col-sm-9">
        {{ $maxTamu }} / sisa {{ $sisaKuota }}

        @if($sisaKuota === 0 && $maxTamu > 0)
            <span class="badge badge-danger ml-2">Kuota Penuh</span>
        @endif
    </dd>

        @if($rapat->jenis_rapat === 'Eksternal')
        <dt class="col-sm-3">Jumlah Instansi Maksimal</dt>
        <dd class="col-sm-9">{{ $rapat->jumlah_instansi ?? '-' }}</dd>
        @endif


        <dt class="col-sm-3">Survey Rapat</dt>
            <dd class="col-sm-9">
                @if($rapat->survey)
                    @php $survey = $rapat->survey; @endphp
                    <p><strong>{{ $survey->judul }}</strong></p>
                    <div class="mb-2">
                        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(
                            $rapat->jenis_rapat === 'Internal'
                                ? route('pegawai.survey.rapat.form.internal', $survey->slug)
                                : route('tamu.survey.rapat.form.eksternal', $survey->slug)
                        ) !!}
                    </div>
                    <span class="badge badge-info mt-2">
                        {{ $survey->respon->count() }} responden telah mengisi
                    </span>
                    <small class="text-muted d-block mt-1">
                        Survey ini dipakai untuk rapat <strong>{{ $rapat->jenis_rapat }}</strong> berjudul <em>{{ $rapat->judul }}</em>.
                    </small>
                @else
                    <span class="text-muted">Belum ada survey rapat terhubung.</span>
                @endif
            </dd>


      <dt class="col-sm-3">QR Code Rapat</dt>
        <dd class="col-sm-9">
        @if($rapat->qr_token_hash && $rapat->qr_token)
            @if($rapat->jenis_rapat === 'Internal')
                @php
                $qrUrl = route('pegawai.rapat.checkin.token', [$rapat->id, $rapat->qr_token]);
            @endphp
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->margin(2)->generate($qrUrl) !!}
            <p class="mt-2 text-muted">
                Pegawai silakan scan QR ini untuk check-in rapat.<br>
            </p>
            <a href="{{ route('admin.rapat.export.qrpdf', $rapat->id) }}" class="btn btn-info btn-sm mr-2">
                    <i class="fas fa-qrcode"></i> Export QR PDF
                </a>
            @elseif($rapat->jenis_rapat === 'Eksternal')
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(250)->generate(
                    route('tamu.rapat.checkin.form', ['rapat'=>$rapat->id, 'token'=>$rapat->qr_token], false)
                ) !!}
                <p class="mt-2 text-muted">Tamu eksternal silakan scan QR ini untuk check-in rapat.</p>
                <a href="{{ route('admin.rapat.export.qrpdf', $rapat->id) }}" class="btn btn-info btn-sm mr-2">
                    <i class="fas fa-qrcode"></i> Export QR PDF
                </a>

            @endif
        @else
            <span class="badge badge-warning">QR belum digenerate</span>
        @endif
        </dd>
    </dl>

    @hasanyrole(['admin', 'pegawai'])
      <div class="mt-3 d-flex">
        @if($rapat->status === 'berjalan')
          <form action="{{ route($prefix.'.rapat.end', $rapat->id) }}" method="POST"
            onsubmit="return confirm('Yakin ingin mengakhiri rapat ini sekarang? Semua peserta hadir akan ditandai selesai.')"
            class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger btn-sm mr-2">
              <i class="fas fa-stop-circle"></i> Akhiri Rapat
            </button>
          </form>
        @endif

        <a href="{{ route($prefix.'.rapat.export.csv', $rapat->id) }}" class="btn btn-success btn-sm mr-2">
          <i class="fas fa-file-csv"></i> Export CSV
        </a>
        <a href="{{ route($prefix.'.rapat.export.pdf', $rapat->id) }}" class="btn btn-danger btn-sm">
          <i class="fas fa-file-pdf"></i> Export PDF
        </a>
      </div>
    @endhasanyrole
  </div>
</div>

{{-- Statistik --}}
@php
  $total   = $rapat->undangan->count();
  $hadir   = $rapat->undangan->where('status_kehadiran','hadir', 'selesai')->count();
  $selesai = $rapat->undangan->where('status_kehadiran','selesai')->count();
  $pending = $rapat->undangan->where('status_kehadiran','pending')->count();
  $tidak   = $rapat->undangan->where('status_kehadiran','tidak_hadir')->count();
@endphp

<div class="row mb-4">
  <div class="col-md-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-users fa-2x text-dark mb-2"></i>
        <h5 class="card-title mb-1">Total</h5>
        <span class="badge badge-dark">{{ $total }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-user-check fa-2x text-success mb-2"></i>
        <h5 class="card-title mb-1">Hadir</h5>
        <span class="badge badge-success">{{ $hadir }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-flag-checkered fa-2x text-secondary mb-2"></i>
        <h5 class="card-title mb-1">Selesai</h5>
        <span class="badge badge-secondary">{{ $selesai }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-hourglass-half fa-2x text-warning mb-2"></i>
        <h5 class="card-title mb-1">Pending</h5>
        <span class="badge badge-warning">{{ $pending }}</span>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center shadow-sm">
      <div class="card-body">
        <i class="fas fa-user-times fa-2x text-danger mb-2"></i>
        <h5 class="card-title mb-1">Tidak Hadir</h5>
        <span class="badge badge-danger">{{ $tidak }}</span>
      </div>
    </div>
  </div>
</div>

{{-- Daftar Undangan --}}
<div class="card">
  <div class="card-header">
    <h4>Daftar Undangan</h4>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>#</th>
            @if($rapat->jenis_rapat === 'Internal')
              <th>Nama Pegawai</th>
              <th>Jabatan</th>
              <th>Status Kehadiran</th>
              <th>Check-in</th>
              <th>Check-out</th>
              <th>Status Survey</th>
              <th>Aksi</th>
            @else
              <th>Nama Instansi</th>
              <th>Kuota</th>
              <th>Jumlah Hadir</th>
              <th>Sisa Kuota</th>
              <th>Aksi</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @if($rapat->jenis_rapat === 'Internal')
            @forelse($rapat->undangan as $undangan)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $undangan->user->name ?? '-' }}</td>
                <td>{{ $undangan->user->pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                <td>{{ ucfirst($undangan->status_kehadiran) }}</td>
                <td>{{ $undangan->checked_in_at ?? '-' }}</td>
                <td>{{ $undangan->checked_out_at ?? '-' }}</td>
                <td>
                @if($undangan->status_survey === 'sudah_isi')
                    <span class="badge badge-success"><i class="fas fa-check"></i> Sudah Isi</span>
                @else
                    <span class="badge badge-warning text-dark"><i class="fas fa-clock"></i> Belum Isi</span>
                @endif
                </td>
                <td>
                  <form action="{{ route($prefix.'.rapat.destroyInvitation', [$rapat->id, $undangan->id]) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center">Belum ada undangan</td></tr>
            @endforelse
                @else
            @forelse($rapat->undanganInstansi as $undanganInstansi)
                <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $undanganInstansi->instansi->nama_instansi ?? '-' }}</td>
                <td>
                    {{-- Form update kuota --}}
                    <form action="{{ route($prefix.'.rapat.updateKuotaInstansi', [$rapat->id, $undanganInstansi->id]) }}"
                    method="POST"
                    class="form-kuota d-flex align-items-center"
                    data-id="{{ $undanganInstansi->id }}">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="kuota" value="{{ $undanganInstansi->kuota }}"
                            class="form-control form-control-sm w-50 me-2" min="1">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                    <span class="ms-2 text-success d-none kuota-status"><i class="fas fa-check"></i> Tersimpan</span>
                    </form>
                </td>
                <td>{{ $undanganInstansi->jumlah_hadir }}</td>
                <td class="sisa-kuota">{{ max(0, $undanganInstansi->kuota - $undanganInstansi->jumlah_hadir) }}</td>
                <td>
                    <a href="{{ route($prefix.'.rapat.detailTamuInstansi', [$rapat->id, $undanganInstansi->id]) }}"
                        class="btn btn-info btn-sm">
                        <i class="fas fa-users"></i> Detail Tamu
                    </a>
                    <form action="{{ route($prefix.'.rapat.destroyInvitationInstansi', [$rapat->id, $undanganInstansi->id]) }}"
                        method="POST" onsubmit="return confirm('Hapus undangan instansi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                    </button>
                    </form>

                </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada instansi diundang</td></tr>
            @endforelse
            @endif
        </tbody>
      </table>
    </div>
  </div>
</div>



<a href="{{ route($prefix.'.rapat.index') }}" class="btn btn-secondary mt-3">Kembali</a>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
  document.querySelectorAll('.form-kuota').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      const url = this.action;
      const data = new FormData(this);
      const statusSpan = this.querySelector('.kuota-status');
      const payload = { kuota: this.querySelector('input[name="kuota"]').value };
      const row = this.closest('tr');

      fetch(url, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
        })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          statusSpan.classList.remove('d-none');
          setTimeout(() => statusSpan.classList.add('d-none'), 2000);
          row.querySelector('input[name="kuota"]').value = res.kuota;
          const sisaCell = row.querySelector('.sisa-kuota');
          if (sisaCell) {
            sisaCell.textContent = res.sisa_kuota;
          }
        } else {
          alert(res.message || 'Gagal update kuota');
        }
      })
      .catch(err => {
        console.error(err);
        alert('Terjadi kesalahan saat update kuota');
      });
    });
  });
});
</script>
@endpush
