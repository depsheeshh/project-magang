@extends('layouts.admin')

@section('title','Admin - History Apel Pagi')

@section('content')
<div class="card card-modern p-4">
  <h4 class="mb-3"><i class="fa-solid fa-clock-rotate-left me-2"></i> History Apel Pagi Pegawai</h4>

    {{-- Filter & Search --}}
  <form method="GET" action="{{ route('admin.apelpagi.index') }}" class="row g-3 mb-3">
    <div class="col-md-3">
      <label class="form-label">Tanggal Mulai</label>
      <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Tanggal Selesai</label>
      <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Cari Pegawai</label>
      <input type="text" name="search" class="form-control" placeholder="NIP / Nama" value="{{ $search }}">
    </div>
    <div class="col-md-3 align-self-end">
      <button type="submit" class="btn btn-primary">
        <i class="fa fa-filter me-2"></i> Terapkan
      </button>
      <a href="{{ route('admin.apelpagi.index') }}" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <a href="{{ route('admin.apelpagi.exportPdf') }}" class="btn btn-danger mb-3">
    <i class="fa fa-file-pdf me-2"></i> Export PDF
  </a>

  <div id="historyTable">
    @include('admin.apelpagi.table', ['history' => $history])
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.querySelector('input[name="search"]');
  const startDateInput = document.querySelector('input[name="start_date"]');
  const endDateInput = document.querySelector('input[name="end_date"]');
  let timer = null;

  function fetchHistory() {
    const query = searchInput.value;
    const startDate = startDateInput.value;
    const endDate = endDateInput.value;

    const url = "{{ route('admin.apelpagi.index') }}" +
      "?search=" + encodeURIComponent(query) +
      "&start_date=" + encodeURIComponent(startDate) +
      "&end_date=" + encodeURIComponent(endDate);

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(response => response.text())
      .then(html => {
        document.getElementById('historyTable').innerHTML = html;
      });
  }

  // realtime search (debounce)
  searchInput.addEventListener('keyup', function() {
    clearTimeout(timer);
    timer = setTimeout(fetchHistory, 300);
  });

  // realtime filter tanggal
  startDateInput.addEventListener('change', fetchHistory);
  endDateInput.addEventListener('change', fetchHistory);
});
</script>
@endpush
