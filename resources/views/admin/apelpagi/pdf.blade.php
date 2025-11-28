<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: "DejaVu Sans", Arial, sans-serif;
      font-size: 12px;
      color: #212529;
      margin: 0;
      padding: 0;
    }
    @page {
      size: A4 portrait;
      margin: 100px 40px 80px 40px;
    }

    /* ===== HEADER ===== */
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
    .kop { margin: 0 80px; text-align: center; }
    .kop h1 { font-size: 18px; font-weight: bold; margin-bottom: 2px; text-transform: uppercase; }
    .kop h2 { font-size: 14px; margin-top: 0; text-transform: uppercase; }
    .kop p { font-size: 11px; margin: 1px 0; }
    .garis-tebal { border-bottom: 3px double #000; margin-top: 8px; }

    /* ===== JUDUL ===== */
    h2.judul { text-align: center; margin: 0; font-size: 15px; text-transform: uppercase; font-weight: bold; }
    .subtitle { text-align: center; font-size: 11px; color: #555; margin-top: 3px; margin-bottom: 20px; }

    /* ===== TABEL ===== */
    table { width: 100%; border-collapse: collapse; margin-top: 5px; }
    th, td { border: 1px solid #b5b5b5; padding: 8px 6px; font-size: 11px; }
    th { background-color: #f2f3f5; font-weight: 700; text-align: center; }
    tr:nth-child(even) { background-color: #fafafa; }
    td { vertical-align: top; }

    .status-badge { padding: 3px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: capitalize; color: #fff; display: inline-block; }
    .status-telat { background-color: #dc2626; }
    .status-ontime { background-color: #16a34a; }

    /* ===== TTD ===== */
    .ttd-wrapper { page-break-inside: avoid; margin-top: 40px; }
    .ttd { width: 260px; float: right; text-align: center; font-size: 12px; }
    .ttd p { margin: 3px 0; }
    .ttd .nama { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    .ttd .jabatan { font-size: 11px; }

    /* ===== FOOTER ===== */
    footer { position: fixed; bottom: -20px; left: 0; right: 0; text-align: right; font-size: 10px; color: #6c757d; }
    .page-number:after { content: counter(page) " / " counter(pages); }

    .dicetak { position: fixed; bottom: 45px; left: 0; right: 0; text-align: center; font-size: 10.5px; color: #555; }
  </style>
</head>
<body>
  <header>
    <img src="{{ public_path('img/logo.png') }}" alt="Logo DKIS">
    <div class="kop">
      <h1>Pemerintah Kota Cirebon</h1>
      <h2>Dinas Komunikasi, Informatika dan Statistik</h2>
      <p>Jl. DR. Sudarsono No.40, Kesambi, Kota Cirebon, Jawa Barat 45134</p>
      <p>Telepon: (0231) xxxxxxx | Email: dkominfo@cirebonkota.go.id</p>
      <div class="garis-tebal"></div>
    </div>
  </header>

  <h2 class="judul">Laporan Kehadiran Apel Pagi</h2>
  <div class="subtitle">
      @if($filters['start_date'] && $filters['end_date'])
          Periode: {{ $filters['start_date'] }} s/d {{ $filters['end_date'] }}
      @else
          Periode: Semua
      @endif
      <br>
      @if($filters['search'])
          Pencarian: {{ $filters['search'] }}
      @endif
  </div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>NIP</th>
        <th>Nama Pegawai</th>
        <th>Bidang</th>
        <th>Tanggal</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($history as $i => $h)
        <tr>
          <td style="text-align:center;">{{ $i+1 }}</td>
          <td>{{ $h->user->pegawai->nip }}</td>
          <td>{{ $h->user->name }}</td>
          <td>{{ $h->user->pegawai->bidang->nama_bidang ?? '-' }}</td>
          <td style="text-align:center;">{{ \Carbon\Carbon::parse($h->tanggal)->format('d/m/Y') }}</td>
          <td style="text-align:center;">
            @if($h->status === 'telat')
              <span class="status-badge status-telat">Telat {{ $h->telat_menit }} menit</span>
            @else
              <span class="status-badge status-ontime">Sesuai Jadwal</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align:center; padding:10px;">Tidak ada data apel pagi</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="ttd-wrapper">
    <div class="ttd">
      <p>Cirebon, {{ now()->translatedFormat('d F Y') }}</p>
      <p class="jabatan">Kepala Dinas Komunikasi, Informatika dan Statistik</p>
      <p class="nama">Ma'ruf Nuryasa, A.P., M.M.</p>
    </div>
  </div>

  <div class="dicetak">
    Dicetak pada: {{ $printed_at }}
  </div>

  <footer>
    Halaman <span class="page-number"></span>
  </footer>
</body>
</html>
