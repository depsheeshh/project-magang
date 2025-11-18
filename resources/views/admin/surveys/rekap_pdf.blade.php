<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rekap Survey {{ ucfirst($periode) }}</title>
  <style>
    /* 🌟 Base Style */
    body {
      font-family: DejaVu Sans, sans-serif;
      background: #ffffff;
      color: #1f2937;
      font-size: 13px;
      margin: 40px;
      line-height: 1.6;
    }

    h1, h2, h3 {
      text-align: center;
      color: #1e3a8a;
      margin-bottom: 4px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .subtitle {
      text-align: center;
      font-size: 12px;
      color: #475569;
      margin-bottom: 25px;
    }

    /* 🌟 Header Line */
    .header-line {
      width: 120px;
      height: 4px;
      background: linear-gradient(90deg, #2563eb, #60a5fa);
      border-radius: 2px;
      margin: 10px auto 25px;
    }

    /* 🌟 Info Box */
    .info-box {
      background: #e8f0ff;
      border-left: 4px solid #2563eb;
      border-radius: 6px;
      padding: 8px 15px;
      margin-bottom: 25px;
      color: #1f2937;
      font-size: 12.5px;
    }
    .info-box strong { color: #1e40af; }

    /* 🌟 Table */
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
      margin-top: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 10px 8px;
      text-align: center;
      border: 1px solid #e5e7eb;
      font-size: 12.5px;
    }

    thead th {
      background: #bfdbfe;
      color: #111;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    tbody tr:nth-child(even) { background-color: #f3f4f6; }
    tbody tr:hover { background-color: #e0f2fe; }

    .rating-badge {
      display: inline-block;
      background-color: #2563eb;
      color: white;
      padding: 4px 10px;
      border-radius: 6px;
      font-size: 11.5px;
      font-weight: 600;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* 🌟 Summary */
    .summary {
      margin-top: 25px;
      text-align: right;
      font-size: 12px;
      color: #374151;
    }

    /* 🌟 Tanda Tangan */
    .ttd-wrapper {
      margin-top: 40px;
    }
    .ttd {
      width: 260px;
      float: right;
      text-align: center;
      font-size: 12px;
    }
    .ttd p { margin: 3px 0; }
    .ttd .nama { margin-top: 60px; font-weight: bold; text-decoration: underline; }
    .ttd .jabatan { font-size: 11px; }

    /* 🌟 Footer */
    footer {
      position: fixed;
      bottom: 10px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 10.5px;
      color: #6c7280;
      border-top: 1px solid #cbd5e1;
      padding-top: 6px;
      line-height: 1.4;
    }

    .page-number:after {
      content: counter(page) " / " counter(pages);
    }

  </style>
</head>
<body>

  <h2>Rekap Survey {{ ucfirst($periode) }}</h2>
  <div class="subtitle">
    Laporan hasil kepuasan pengunjung berdasarkan periode {{ strtolower($periode) }}
  </div>

  <div class="header-line"></div>

  <div class="info-box">
    <p><strong>Instansi :</strong> Dinas Komunikasi, Informatika dan Statistik Kota Cirebon</p>
    <p><strong>Periode :</strong> {{ ucfirst($periode) }}</p>
  </div>

  <table>
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
      @foreach($rekap as $r)
        <tr>
          @if($periode==='harian')
            <td>{{ \Carbon\Carbon::parse($r->tanggal)->format('d-m-Y') }}</td>
          @elseif($periode==='bulanan')
            <td>{{ $r->bulan }}/{{ $r->tahun }}</td>
          @else
            <td>{{ $r->tahun }}</td>
          @endif
          <td>{{ $r->total }}</td>
          <td><span class="rating-badge">{{ number_format($r->avg_rating,2) }}</span></td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="summary">
    Dicetak pada: {{ now()->format('d M Y, H:i') }}
  </div>

  <!-- 🌟 Bagian Tanda Tangan (Sama seperti Rekap Rapat) -->
  <div class="ttd-wrapper">
    <div class="ttd">
      <p>Cirebon, {{ now()->translatedFormat('d F Y') }}</p>
      <p class="jabatan">Kepala Dinas Komunikasi, Informatika dan Statistik</p>
      <p class="nama">________________________</p>
      <p class="nama">Ma'ruf Nuryasa., A.P.,M.M.</p>
    </div>
  </div>

  <footer>
    Halaman <span class="page-number"></span><br>
    <span style="font-size:9px; color:#888;">
      Laporan ini dicetak otomatis dari Sistem Buku Tamu Digital — DKIS Kota Cirebon
    </span>
  </footer>

</body>
</html>
