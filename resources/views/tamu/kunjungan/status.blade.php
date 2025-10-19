@extends('layouts.admin')

@section('title','Status Kunjungan')
@section('page-title','Status Kunjungan Saya')

@section('content')
<div class="card">
  <div class="card-header"><h4>Status Kunjungan</h4></div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Pegawai Tujuan</th>
          <th>Keperluan</th>
          <th>Status</th>
          <th>Alasan Penolakan</th>
          <th>Waktu Masuk</th>
          <th>Waktu Keluar</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kunjungan as $k)
          <tr>
            <td>{{ $k->pegawai?->user?->name ?? '-' }}</td>
            <td>{{ $k->keperluan }}</td>
            <td>
              @if($k->status === 'diterima')
                <span class="badge badge-success">Diterima</span>
              @elseif($k->status === 'ditolak')
                <span class="badge badge-danger">Ditolak</span>
              @elseif($k->status === 'sedang_bertamu')
                <span class="badge badge-warning">Sedang Bertamu</span>
              @else
                <span class="badge badge-secondary">{{ ucfirst($k->status) }}</span>
              @endif
            </td>
            <td>
              @if($k->status === 'ditolak')
                {{ $k->alasan_penolakan ?? '-' }}
              @else
                -
              @endif
            </td>
            <td>{{ $k->waktu_masuk }}</td>
            <td>{{ $k->waktu_keluar ?? '-' }}</td>
            <td>
                @if($k->status === 'sedang_bertamu')
                    <form action="{{ route('tamu.kunjungan.checkout',$k->id) }}" method="POST" style="display:inline" data-id="{{ $k->id }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Checkout
                    </button>
                    </form>
                @else
                    <span class="badge badge-secondary">{{ ucfirst($k->status) }}</span>
                @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center">Belum ada kunjungan</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Survey -->
@section('modals')
<div class="modal fade" id="surveyModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="surveyForm" action="javascript:void(0);">
        @csrf
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Survey Kepuasan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="kunjungan_id" id="surveyKunjunganId">

          <div class="mb-3">
            <label>Kemudahan Proses Registrasi</label>
            <select name="kemudahan_registrasi" class="form-control" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label>Keramahan Pelayanan</label>
            <select name="keramahan_pelayanan" class="form-control" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label>Waktu Tunggu</label>
            <select name="waktu_tunggu" class="form-control" required>
              <option value="">-- Pilih --</option>
              @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}">{{ $i }}</option>
              @endfor
            </select>
          </div>

          <div class="mb-3">
            <label>Rating Umum</label>
            <select name="rating" class="form-control" required>
              <option value="">-- Pilih --</option>
              <option value="1">1 - Sangat Buruk</option>
              <option value="2">2 - Buruk</option>
              <option value="3">3 - Cukup</option>
              <option value="4">4 - Baik</option>
              <option value="5">5 - Sangat Baik</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Saran / Masukan</label>
            <textarea name="saran" class="form-control" rows="3"></textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Kirim</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Intercept semua form checkout
    document.querySelectorAll('form[action*="checkout"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const kunjunganId = this.dataset.id;

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(res => {
                if (!res.ok) {
                    return res.json().then(err => { throw err; });
                }
                return res.json();
            })
            .then(data => {
                toastr.success('Checkout berhasil, silakan isi survey.');
                document.getElementById('surveyKunjunganId').value = kunjunganId;
                $('#surveyModal').modal('show'); // ✅ Bootstrap 4 cara show modal
            })
            .catch(err => {
                toastr.error(err.message || 'Terjadi kesalahan saat checkout.');
            });
        });
    });

    // Submit survey
    document.getElementById('surveyForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const kunjunganId = document.getElementById('surveyKunjunganId').value;
        const formData = new FormData(this);

        fetch(`/tamu/kunjungan/${kunjunganId}/survey`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(res => {
            if (!res.ok) {
                return res.json().then(err => { throw err; });
            }
            return res.json();
        })
        .then(data => {
            toastr.success('Terima kasih, survey Anda tersimpan.');
            $('#surveyModal').modal('hide');
            setTimeout(() => location.reload(), 1000);
        })
        .catch(err => {
            toastr.error(err.message || 'Terjadi kesalahan saat menyimpan survey.');
        });
    });

});
</script>
@endpush
