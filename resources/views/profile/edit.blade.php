@extends('layouts.admin')

@section('title','Profil Saya')
@section('page-title','Profil Saya')

@section('content')
<style>
/* ===========================
   THEME VARIABLES
   =========================== */
:root {
  --card-bg: #ffffff;
  --card-text: #1a1a1a;
  --card-shadow: rgba(0, 0, 0, 0.1);

  --header-bg: linear-gradient(90deg, #0d6efd, #3ba9ff);
  --header-text: #fff;

  --input-bg: #f8f9fc;
  --input-border: rgba(0, 0, 0, 0.15);
  --input-text: #1a1a1a;
  --input-focus-border: #007bff;

  --section-title: #0d6efd;
  --btn-bg: linear-gradient(135deg, #0d6efd, #3ba9ff);
  --btn-text: #fff;
  --btn-shadow: rgba(13, 110, 253, 0.35);
}

/* ===========================
   DARK MODE OVERRIDE
   =========================== */
body.dark,
html[data-theme="dark"] {
  --card-bg: linear-gradient(145deg, #1b1b2f, #1f1f3b);
  --card-text: #e0e0e0;
  --card-shadow: rgba(0, 0, 0, 0.3);

  --header-bg: linear-gradient(90deg, #0066ff, #00b4ff);
  --header-text: #fff;

  --input-bg: rgba(30, 40, 60, 0.8);
  --input-border: rgba(0, 170, 255, 0.25);
  --input-text: #e8f1ff;
  --input-focus-border: #00bfff;

  --section-title: #a7c7ff;
  --btn-bg: linear-gradient(135deg, #00aaff, #0066ff);
  --btn-text: #fff;
  --btn-shadow: rgba(0, 157, 255, 0.35);
}

/* ===========================
   CARD PROFILE
   =========================== */
.card-profile {
  background: var(--card-bg);
  color: var(--card-text);
  border-radius: 20px;
  border: none;
  box-shadow: 0 8px 20px var(--card-shadow);
  transition: all 0.3s ease;
}
.card-profile:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px var(--btn-shadow);
}

/* HEADER */
.card-header-profile {
    background: var(--header-bg);
    color: var(--header-text);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 12px;
    border-radius: 20px;
    border: none;
}
.card-header-profile i {
  font-size: 2.2rem;
}

/* INPUT FIELD */
.form-control,
textarea.form-control {
  background: var(--input-bg);
  border: 1px solid var(--input-border);
  color: var(--input-text);
  border-radius: 12px;
  transition: 0.25s ease;
}
.form-control:focus,
textarea.form-control:focus {
  border-color: var(--input-focus-border);
  box-shadow: 0 0 10px var(--input-focus-border);
  background: var(--input-bg);
}

/* LABEL */
label {
  color: var(--section-title);
}

/* SECTION TITLE */
h5.text-section {
  border-left: 4px solid var(--section-title);
  padding-left: 10px;
  margin-top: 30px;
  margin-bottom: 20px;
  font-weight: 600;
  color: var(--section-title);
}

/* BUTTON */
.btn-save {
  background: var(--btn-bg);
  color: var(--btn-text);
  padding: 12px 28px;
  font-weight: 600;
  border-radius: 12px;
  border: none;
  box-shadow: 0 0 20px var(--btn-shadow);
  transition: 0.3s ease;
}
.btn-save:hover {
  transform: translateY(-2px);
  box-shadow: 0 0 25px var(--btn-shadow);
}

/* ANIMATION */
.card-body {
  animation: fadeInUp 0.6s ease forwards;
}
/* Foto Profil */
.profile-photo {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #fff;
  box-shadow: 0 0 10px rgba(0,0,0,0.15);
}

.modal-body {
  overflow-y: auto;
  padding: 1rem;
}

@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(15px); }
  to { opacity: 1; transform: translateY(0); }
}

</style>


<div class="card card-profile shadow-lg">
  <div class="card-header-profile">
    <h5>Profil Saya</h5>
  </div>

  <div class="text-center my-3">
    <img id="preview-photo"
        src="{{ $user->profile_photo
                ? asset('storage/'.$user->profile_photo)
                : asset('admin/assets/img/avatar/avatar-1.png') }}"
        alt="Foto Profil"
        style="max-width: 300px; height:auto;">
    </div>

    <div class="d-flex justify-content-center mb-3">
        <form action="{{ route('profile.resetPhoto') }}" method="POST" id="reset-form">
            @csrf @method('PUT')
            <button type="submit" id="reset-photo" class="btn btn-sm btn-danger">
            <i class="fas fa-times"></i> Reset Foto
            </button>
        </form>
    </div>

  <div class="card-body p-4">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
      @csrf @method('PUT')

      {{-- Upload Foto Profil --}}
      <div class="form-group mb-4">
        <label for="profile_photo">Upload Foto Profil</label>
        <input type="file" name="profile_photo" id="profile_photo" class="form-control">
        </div>

      {{-- Data Umum --}}
      <h5 class="text-section"><i class="fas fa-id-card me-2"></i> Data Umum</h5>
      <div class="form-floating mb-3">
        <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-control" id="name" required>
        <label for="name">Nama Lengkap</label>
      </div>
      <div class="form-floating mb-4">
        <input type="email" name="email" value="{{ old('email',$user->email) }}" class="form-control" id="email" required>
        <label for="email">Email</label>
      </div>

      {{-- Role Tamu --}}
      @if($user->hasRole('tamu'))
        <h5 class="text-section"><i class="fas fa-user-friends me-2"></i> Data Tamu</h5>
        <div class="form-floating mb-3">
          <select id="instansi_select" class="form-control" required>
            <option value="">-- Pilih Instansi --</option>
            @foreach($instansi as $i)
              <option value="{{ $i->nama_instansi }}"
                @if(old('instansi',$user->tamu->instansi ?? '') === $i->nama_instansi) selected @endif>
                {{ $i->nama_instansi }}
              </option>
            @endforeach
            <option value="lainnya"
              @if(!in_array(old('instansi',$user->tamu->instansi ?? ''), $instansi->pluck('nama_instansi')->toArray())) selected @endif>
              Lainnya
            </option>
          </select>
          <label for="instansi_select">Instansi Tamu</label>
          <input type="hidden" name="instansi" id="instansi_hidden"
                 value="{{ old('instansi',$user->tamu->instansi ?? '') }}">
        </div>

        <div class="form-floating mb-3 d-none" id="instansi_lainnya_wrapper">
          <input type="text" id="instansi_lainnya" class="form-control"
                 placeholder="Masukkan instansi Anda"
                 value="{{ !in_array(old('instansi',$user->tamu->instansi ?? ''), $instansi->pluck('nama_instansi')->toArray())
                            ? old('instansi',$user->tamu->instansi ?? '') : '' }}">
          <label for="instansi_lainnya">Instansi Lainnya</label>
        </div>

        <div class="form-floating mb-3">
          <input type="text" name="no_hp" value="{{ old('no_hp',$user->tamu->no_hp ?? '') }}" class="form-control" id="no_hp">
          <label for="no_hp">No HP</label>
        </div>
        <div class="form-floating mb-4">
          <textarea name="alamat" class="form-control" id="alamat" style="height: 100px">{{ old('alamat',$user->tamu->alamat ?? '') }}</textarea>
          <label for="alamat">Alamat</label>
        </div>
      @endif

      {{-- Role Pegawai --}}
      @if($user->hasRole('pegawai'))
        <h5 class="text-section"><i class="fas fa-briefcase me-2"></i> Data Pegawai</h5>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" value="{{ $user->pegawai?->bidang?->nama_bidang ?? '-' }}" id="bidang" disabled>
          <label for="bidang">Bidang</label>
        </div>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" value="{{ $user->pegawai?->jabatan?->nama_jabatan ?? '-' }}" id="jabatan" disabled>
          <label for="jabatan">Jabatan</label>
        </div>
        <div class="form-floating mb-4">
          <input type="text" name="telepon" value="{{ old('telepon',$user->pegawai->telepon ?? '') }}" class="form-control" id="telepon">
          <label for="telepon">No HP</label>
        </div>
      @endif

      {{-- Role Admin --}}
      @if($user->hasRole('admin'))
        <div class="alert alert-info border-0 rounded-3 shadow-sm">
          <i class="fas fa-info-circle me-2"></i>
          Sebagai admin, Anda hanya bisa mengubah nama & email di sini.
        </div>
      @endif

      {{-- Tombol Simpan --}}
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-save">
          <i class="fas fa-save me-2"></i> Simpan Perubahan
        </button>
      </div>
    </form>
  </div>
</div>


@endsection
@section('modals')
<div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="cropModalLabel">Crop Foto Profil</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img id="crop-image" style="max-width:100%; max-height:60vh; display:block;" alt="Crop area">
        <input type="range" id="crop-zoom" min="0.5" max="3" step="0.01" value="1" class="mt-3 w-75">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Batal
        </button>
        <button type="button" class="btn btn-success" id="crop-save">
          <i class="fas fa-check"></i> Simpan Hasil Crop
        </button>
      </div>
    </div>
  </div>
</div>
@endsection


@push('scripts')
<script>
const select = document.getElementById('instansi_select');
const wrapper = document.getElementById('instansi_lainnya_wrapper');
const inputLainnya = document.getElementById('instansi_lainnya');
const hiddenField = document.getElementById('instansi_hidden');

function toggleInstansi() {
  if (!select) return;
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

if(select){
  select.addEventListener('change', toggleInstansi);
}
if(inputLainnya){
  inputLainnya.addEventListener('input', () => hiddenField.value = inputLainnya.value);
}
toggleInstansi();

// === Cropper logic ===
$(function(){
  const photoInput   = document.getElementById('profile_photo');
  const previewPhoto = document.getElementById('preview-photo');
  const cropImage    = document.getElementById('crop-image');
  const cropSaveBtn  = document.getElementById('crop-save');
  const zoomSlider   = document.getElementById('crop-zoom');
  const updateForm   = $('form[action="{{ route('profile.update') }}"]')[0];
  let cropper;

  // pilih file → set src gambar crop
  photoInput.addEventListener('change', function(e){
    const file = e.target.files[0];
    if(!file) return;
    const reader = new FileReader();
    reader.onload = function(ev){
      cropImage.src = ev.target.result;
      // buka modal setelah src di-set
      $('#cropModal').modal('show');
    };
    reader.readAsDataURL(file);
  });

  // init cropper saat modal tampil
  $('#cropModal').on('shown.bs.modal', function(){
    if(cropper) cropper.destroy();
    cropper = new Cropper(cropImage, {
      aspectRatio: 1,
      viewMode: 1,
      autoCropArea: 1,
      background: false,
      responsive: true,
      dragMode: 'move',
      movable: true,
      zoomable: true,
    });
  });

  // slider zoom
  zoomSlider.addEventListener('input', function(){
    if(cropper){
      cropper.zoomTo(parseFloat(this.value));
    }
  });

  // simpan hasil crop
  cropSaveBtn.addEventListener('click', function(){
    if(!cropper) return;
    cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob(function(blob){
      const url = URL.createObjectURL(blob);
      previewPhoto.src = url;
      const file = new File([blob], "profile_photo.png", { type: "image/png" });
      const dt = new DataTransfer();
      dt.items.add(file);
      photoInput.files = dt.files;
      $('#cropModal').modal('hide');
    });
  });

  // bersihkan saat modal ditutup
  $('#cropModal').on('hidden.bs.modal', function(){
    if(cropper){ cropper.destroy(); cropper = null; }
    zoomSlider.value = 1;
  });

  // submit form update → pastikan file hasil crop dikirim
  updateForm.addEventListener('submit', function(e){
    if(cropper){
      e.preventDefault();
      cropper.getCroppedCanvas({ width: 400, height: 400 }).toBlob(function(blob){
        const file = new File([blob], "profile_photo.png", { type: "image/png" });
        const dt = new DataTransfer();
        dt.items.add(file);
        photoInput.files = dt.files;
        updateForm.submit();
      });
    }
  });
});
</script>

@endpush
