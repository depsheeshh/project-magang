<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rekap Rapat</title>
  <style>
    body {
      font-family: "DejaVu Sans", Arial, sans-serif;
      font-size: 12px;
      color: #212529;
      line-height: 1.6;
      background-color: #fff;
      margin: 0;
      padding: 0;
    }

    @page { size: A4 landscape; margin: 100px 40px 80px 40px; }

    header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
      margin-bottom: 20px;
      position: relative;
    }
    header img {
      position: absolute;
      left: 40px; top: 10px;
      width: 70px; height: 70px;
    }
    .kop { margin: 0 80px; text-align: center; }
    .kop h1 { font-size: 18px; font-weight: bold; margin-bottom: 2px; text-transform: uppercase; }
    .kop h2 { font-size: 14px; margin: 0; text-transform: uppercase; }
    .kop p { font-size: 11px; margin: 1px 0; }
    .garis-tebal { border-bottom: 3px double #000; margin-top: 8px; }

    h2.judul {
      text-align: center;
      margin: 0;
      font-size: 15px;
      text-transform: uppercase;
      font-weight: bold;
      letter-spacing: 0.3px;
    }
    .subtitle {
      text-align: center;
      font-size: 11px;
      color: #555;
      margin-top: 3px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 5px;
    }
    th, td {
      border: 1px solid #b5b5b5;
      padding: 8px 6px;
      font-size: 11px;
      text-align: center;
    }
    th { background-color: #f2f3f5; font-weight: 700; color: #111; }
    tr:nth-child(even) { background-color: #fafafa; }

    .status {
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 10.5px;
      font-weight: bold;
      text-transform: capitalize;
      display: inline-block;
      color: #fff;
    }
    .status-selesai { background-color: #16a34a; }
    .status-berjalan { background-color: #2563eb; }
    .status-dibatalkan { background-color: #dc2626; }

    .ttd-wrapper { margin-top: 40px; }
    .ttd { width: 260px; float: right; text-align: center; font-size: 12px; }
    .ttd p { margin: 3px 0; }
    .ttd .nama { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    .ttd .jabatan { font-size: 11px; }

    footer {
      position: fixed;
      bottom: -20px;
      left: 0; right: 0;
      text-align: right;
      font-size: 10px;
      color: #6c757d;
    }
    .page-number:after { content: counter(page) " / " counter(pages); }

    .dicetak {
      position: fixed;
      bottom: 45px;
      left: 0; right: 0;
      text-align: center;
      font-size: 10.5px;
      color: #555;
    }
  </style>
</head>
<body>
  <header>
    <img src="{{ public_path('img/logo.png') }}" alt="Logo">
    <div class="kop">
      <h1>PEMERINTAH KOTA CIREBON</h1>
      <h2>DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK</h2>
      <p>Jl. DR. Sudarsono No.40, Kesambi, Kota Cirebon, Jawa Barat 45134</p>
      <p>Telepon: (0231) 123456 &nbsp; | &nbsp; Email: diskominfo@cirebonkota.go.id</p>
      <div class="garis-tebal"></div>
    </div>
  </header>

  <h2 class="judul">Rekap Rapat</h2>
  <div class="subtitle">
    @if(request('start_date') && request('end_date'))
        Periode: {{ request('start_date') }} s/d {{ request('end_date') }}
    @else
        Periode: Semua
    @endif
    <br>
    @if(request('status'))
        Status Rapat: {{ ucfirst(request('status')) }}
    @else
        Status Rapat: Semua
    @endif
  </div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Judul Rapat</th>
        <th>Waktu</th>
        <th>Lokasi</th>
        <th>Status</th>
        <th>Total Undangan</th>
        <th>Hadir</th>
        <th>Selesai</th> {{-- ✅ kolom baru --}}
        <th>Tidak Hadir</th>
        <th>Pending</th>
        <th>% Hadir</th>
      </tr>
    </thead>
    <tbody>
      @forelse($rekap as $r)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td style="text-align:left;">{{ $r['judul'] }}</td>
        <td>{{ $r['waktu'] }}</td>
        <td>{{ $r['lokasi'] }}</td>
        <td>
          <span class="status status-{{ Str::slug($r['status'],'_') }}">
            {{ $r['status'] }}
          </span>
        </td>
        <td>{{ $r['total'] }}</td>
        <td>{{ $r['hadir'] }}</td>
        <td>{{ $r['selesai'] ?? 0 }}</td> {{-- ✅ --}}
        <td>{{ $r['tidak'] }}</td>
        <td>{{ $r['pending'] }}</td>
        <td>
          {{ $r['total'] > 0 ? round((($r['hadir'] + ($r['selesai'] ?? 0)) / $r['total']) * 100, 1) . '%' : '-' }}
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="11" style="text-align:center;">Tidak ada data rapat</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  {{-- Ringkasan total --}}
  @if($rekap->count() > 0)
  <table style="margin-top:20px; font-size:12px; width:60%; border-collapse:collapse;">
      <tr>
          <th style="border:1px solid #b5b5b5; padding:6px;">Total Rapat</th>
          <td style="border:1px solid #b5b5b5; padding:6px;">{{ $rekap->count() }}</td>
      </tr>
      <tr>
          <th style="border:1px solid #b5b5b5; padding:6px;">Total Undangan</th>
          <td style="border:1px solid #b5b5b5; padding:6px;">{{ $rekap->sum('total') }}</td>
      </tr>
      <tr>
          <th style="border:1px solid #b5b5b5; padding:6px;">Total Hadir</th>
          <td style="border:1px solid #b5b5b5; padding:6px;">{{ $rekap->sum('hadir') }}</td>
      </tr>
      <tr>
          <th style="border:1px solid #b5b5b5; padding:6px;">Total Selesai</th>
          <td style="border:1px solid #b5b5b5; padding:6px;">{{ $rekap->sum('selesai') }}</td>
      </tr>
            <tr>
          <th style="border:1px solid #b5b5b5; padding:6px;">Total Tidak Hadir</th>
          <td style="border:1px solid #b5b5b5; padding:6px;">{{ $rekap->sum('tidak') }}</td>
      </tr>
      <tr>
          <th style="border:1px solid #b5b5b5; padding:6px;">Total Pending</th>
          <td style="border:1px solid #b5b5b5; padding:6px;">{{ $rekap->sum('pending') }}</td>
      </tr>
  </table>
  @endif

  <div class="ttd-wrapper">
    <div class="ttd">
      <p>Cirebon, {{ now()->translatedFormat('d F Y') }}</p>
      <p class="jabatan">Kepala Dinas Komunikasi, Informatika dan Statistik</p>
      <p class="nama">________________________</p>
    </div>
  </div>

  <div class="dicetak">
    Dicetak pada: {{ now()->format('d/m/Y H:i') }}
  </div>

  <footer>
    Halaman <span class="page-number"></span>
    <br>
    <span style="font-size:9px; color:#999;">
      Laporan ini dicetak otomatis dari Sistem Buku Tamu Digital
    </span>
  </footer>
</body>
</html>
