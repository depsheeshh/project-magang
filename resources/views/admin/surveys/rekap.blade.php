@extends('layouts.admin')

@section('title','Rekap Survey')
@section('page-title','Rekap Hasil Survey')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
  <h4>Rekap Survey {{ ucfirst($periode) }}</h4>
  <div>
    <div class="btn-group mr-2">
      <a href="{{ route('admin.surveys.rekap',['periode'=>'harian']) }}" class="btn btn-sm btn-outline-primary {{ $periode==='harian'?'active':'' }}">Harian</a>
      <a href="{{ route('admin.surveys.rekap',['periode'=>'bulanan']) }}" class="btn btn-sm btn-outline-primary {{ $periode==='bulanan'?'active':'' }}">Bulanan</a>
      <a href="{{ route('admin.surveys.rekap',['periode'=>'tahunan']) }}" class="btn btn-sm btn-outline-primary {{ $periode==='tahunan'?'active':'' }}">Tahunan</a>
    </div>
    <a href="{{ route('admin.surveys.export.pdf',$periode) }}" class="btn btn-sm btn-danger">
      <i class="fas fa-file-pdf"></i> Export PDF
    </a>
  </div>
</div>
  <div class="card-body">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          @if($periode==='harian')
            <th>Tanggal</th>
          @elseif($periode==='bulanan')
            <th>Bulan</th>
          @else
            <th>Tahun</th>
          @endif
          <th>Total Survey</th>
          <th>Rata-rata Rating</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rekap as $r)
          <tr>
            @if($periode==='harian')
              <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
            @elseif($periode==='bulanan')
              <td>{{ $r->bulan }}/{{ $r->tahun }}</td>
            @else
              <td>{{ $r->tahun }}</td>
            @endif
            <td>{{ $r->total }}</td>
            <td>{{ number_format($r->avg_rating,2) }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>
        @endforelse
      </tbody>
    </table>

    <hr>
    <h5>Visualisasi</h5>
    <canvas id="surveyChart" height="100"></canvas>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('surveyChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [
            {
                label: 'Total Survey',
                data: @json($totals),
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                yAxisID: 'y',
            },
            {
                label: 'Rata-rata Rating',
                data: @json($avgs),
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        stacked: false,
        scales: {
            y: {
                type: 'linear',
                position: 'left',
                title: { display: true, text: 'Total Survey' }
            },
            y1: {
                type: 'linear',
                position: 'right',
                min: 0,
                max: 5,
                title: { display: true, text: 'Rata-rata Rating' },
                grid: { drawOnChartArea: false }
            }
        }
    }
});
</script>
@endpush
