<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Kehadiran Rapat</title>
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

    @page { size: A4 portrait; margin: 100px 40px 80px 40px; }

    /* === HEADER / KOP === */
    header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 8px;
      margin-bottom: 20px;
      position: relative;
    }
    header img {
      position: absolute;
      left: 40px;
      top: 10px;
      width: 70px;
      height: 70px;
    }
    .kop {
      margin: 0 80px;
      text-align: center;
    }
    .kop h1 { font-size: 18px; font-weight: bold; margin-bottom: 2px; text-transform: uppercase; }
    .kop h2 { font-size: 14px; margin: 0; text-transform: uppercase; }
    .kop p { font-size: 11px; margin: 1px 0; }
    .garis-tebal { border-bottom: 3px double #000; margin-top: 8px; }

    /* === JUDUL & INFO RAPAT === */
    h2.judul {
      text-align: center;
      margin: 0;
      font-size: 15px;
      text-transform: uppercase;
      font-weight: bold;
      letter-spacing: 0.3px;
    }
    .info {
      background: #f3f8ff;
      border-left: 4px solid #2563eb;
      border-radius: 6px;
      padding: 10px 15px;
      margin: 20px 0;
      font-size: 12.5px;
      color: #111827;
    }
    .info strong { color: #1e3a8a; }

    /* === TABLE === */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #b5b5b5;
      padding: 8px 6px;
      font-size: 11px;
      text-align: center;
    }
    th { background-color: #f2f3f5; font-weight: 700; color: #111; }
    tr:nth-child(even) { background-color: #fafafa; }

    /* === STATUS BADGE === */
    .status {
      padding: 4px 10px;
      border-radius: 6px;
      font-weight: 600;
      font-size: 11px;
      color: #fff;
      display: inline-block;
    }
    .hadir { background: #16a34a; }
    .tidak_hadir { background: #dc2626; }
    .pending { background: #facc15; color: #111827; }

    /* === TTD === */
    .ttd-wrapper { page-break-inside: avoid; margin-top: 40px; }
    .ttd {
      width: 260px;
      float: right;
      text-align: center;
      font-size: 12px;
    }
    .ttd p { margin: 3px 0; }
    .ttd .nama { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    .ttd .jabatan { font-size: 11px; }

    /* === FOOTER === */
    footer {
      position: fixed;
      bottom: -20px;
      left: 0;
      right: 0;
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

  <h2 class="judul">Laporan Kehadiran Rapat</h2>

  <div class="info">
    <p><strong>Judul Rapat :</strong> {{ $rapat->judul }}</p>
    <p><strong>Waktu :</strong> {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
      s/d {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}</p>
    <p><strong>Lokasi :</strong> {{ $rapat->lokasi ?? '-' }}</p>
    <p><strong>Jumlah Undangan :</strong> {{ $rapat->undangan->count() }} orang</p>
  </div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Peserta</th>
        <th>Instansi</th>
        <th>Status Kehadiran</th>
        <th>Waktu Check-in</th>
      </tr>
    </thead>
    <tbody>
      @forelse($undangan as $u)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $u->user->name ?? '-' }}</td>
        <td>{{ $u->instansi->nama_instansi ?? '-' }}</td>
        <td>
          <span class="status {{ $u->status_kehadiran }}">
            {{ ucfirst(str_replace('_',' ',$u->status_kehadiran)) }}
          </span>
        </td>
        <td>{{ optional($u->checked_in_at)->format('d/m/Y H:i') ?? '-' }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="5" style="text-align:center;">Tidak ada data kehadiran</td>
      </tr>
      @endforelse
    </tbody>
  </table>

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
  </footer>
</body>
</html>
