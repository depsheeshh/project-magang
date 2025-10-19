<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Rekap Survey {{ ucfirst($periode) }}</title>
  <style>
    /* 🌟 Base Style */
    body {
      font-family: DejaVu Sans, sans-serif;
      background: #f8fafc;
      color: #1e293b;
      font-size: 13px;
      margin: 40px;
    }

    h1, h2, h3 {
      text-align: center;
      color: #0f172a;
      margin-bottom: 5px;
    }

    .subtitle {
      text-align: center;
      font-size: 12px;
      color: #64748b;
      margin-bottom: 30px;
    }

    /* 🌟 Table Styling */
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 10px 8px;
      text-align: center;
      border-bottom: 1px solid #e2e8f0;
    }

    th {
      background: linear-gradient(90deg, #2563eb, #3b82f6);
      color: #fff;
      font-weight: bold;
      text-transform: uppercase;
      font-size: 12px;
      letter-spacing: 0.5px;
    }

    tr:nth-child(even) {
      background-color: #f1f5f9;
    }

    tr:hover {
      background-color: #e0f2fe;
    }

    /* 🌟 Footer */
    footer {
      position: fixed;
      bottom: 10px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 11px;
      color: #94a3b8;
    }

    /* 🌟 Badge / Highlight Styling */
    .rating-badge {
      display: inline-block;
      background-color: #2563eb;
      color: white;
      padding: 3px 8px;
      border-radius: 6px;
      font-size: 11px;
      font-weight: bold;
    }

    .summary {
      margin-top: 25px;
      text-align: right;
      font-size: 12.5px;
      color: #334155;
    }

    /* 🌟 Header Decoration */
    .header-line {
      width: 100px;
      height: 4px;
      background: linear-gradient(90deg, #3b82f6, #60a5fa);
      margin: 10px auto 20px;
      border-radius: 2px;
    }
  </style>
</head>
<body>
  <h2>Rekap Survey {{ ucfirst($periode) }}</h2>
  <div class="subtitle">Laporan hasil kepuasan pengunjung berdasarkan periode {{ strtolower($periode) }}</div>
  <div class="header-line"></div>

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
          <td>
            <span class="rating-badge">{{ number_format($r->avg_rating,2) }}</span>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="summary">
    Dicetak pada: {{ now()->format('d M Y, H:i') }}
  </div>

  <footer>
    &copy; {{ date('Y') }} Sistem Informasi Kunjungan — UCIC
  </footer>
</body>
</html>
