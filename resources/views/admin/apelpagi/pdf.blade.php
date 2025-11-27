<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">

  <style>
     body {
    font-family: "DejaVu Sans", Arial, sans-serif;
    color: #1f2937;
    font-size: 12px;
    margin: 0;
    padding: 0;
  }

  @page {
    size: A4 portrait;
    margin: 140px 40px 80px 40px; /* dinaikkan dari 110px → 140px */
  }

  /* ===== HEADER RESMI DKIS ===== */
  header {
    position: fixed;
    top: -110px; /* turunkan sedikit dari -80px → -110px */
    left: 0;
    right: 0;
    text-align: center;
  }

  header img {
    position: absolute;
    left: 40px;
    top: 5px;
    width: 75px;
    height: 75px;
  }

  .kop {
    margin: 0 80px;
  }

  .kop h1 {
    font-size: 18px;
    margin: 0;
    font-weight: bold;
    text-transform: uppercase;
  }

  .kop h2 {
    font-size: 14px;
    margin: 2px 0;
    font-weight: bold;
  }

  .kop p {
    margin: 2px 0;
    font-size: 11px;
  }

  .garis-atas {
    border-bottom: 2px solid #000;
    margin-top: 10px; /* tambah jarak */
  }

  .garis-bawah {
    border-bottom: 1px solid #000;
    margin-top: 4px; /* tambah jarak */
  }

  /* ===== JUDUL ===== */
  h2.judul {
    text-align: center;
    margin-top: 30px !important; /* tambah jarak supaya aman */
    font-size: 15px;
    font-weight: bold;
    text-transform: uppercase;
  }

  .subtitle {
    text-align: center;
    font-size: 11px;
    margin-bottom: 16px;
    color: #555;
  }

    /* ======================= TABEL ======================= */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 11.5px;
    }

    th, td {
      border: 1px solid #b5b5b5;
      padding: 7px 6px;
      text-align: left;
    }

    th {
      background-color: #e8eef7;
      font-weight: 700;
      text-align: center;
    }

    tr:nth-child(even) {
      background-color: #f7f9fc;
    }

    .status-badge {
      padding: 3px 6px;
      border-radius: 4px;
      font-size: 10px;
      font-weight: bold;
      text-transform: capitalize;
      color: #fff;
      display: inline-block;
    }

    .status-telat {
      background-color: #dc2626;
    }
    .status-ontime {
      background-color: #16a34a;
    }

    /* ======================= TTD ======================= */
    .ttd-wrapper {
      margin-top: 40px;
      width: 100%;
    }

    .ttd {
      width: 260px;
      float: right;
      text-align: center;
      font-size: 12px;
    }

    .ttd .nama {
      margin-top: 60px;
      font-weight: bold;
      text-decoration: underline;
    }

    .ttd .jabatan {
      font-size: 11px;
    }

    /* ======================= FOOTER ======================= */
    footer {
      position: fixed;
      bottom: -10px;
      left: 0; right: 0;
      text-align: center;
      font-size: 10px;
      color: #6b7280;
      border-top: 1px solid #d1d5db;
      padding-top: 5px;
    }
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

    <div class="garis-atas"></div>
    <div class="garis-bawah"></div>
  </div>
</header>


<h2 class="judul">Laporan Kehadiran Apel Pagi</h2>
<div class="subtitle">
  Dicetak pada: {{ now()->format('d/m/Y H:i') }}
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
    @foreach($history as $h)
      <tr>
        <td style="text-align:center;">{{ $loop->iteration }}</td>
        <td>{{ $h->user->pegawai->nip }}</td>
        <td>{{ $h->user->name }}</td>
        <td>{{ $h->user->pegawai->bidang->nama_bidang ?? '-' }}</td>
        <td>{{ \Carbon\Carbon::parse($h->tanggal)->format('d/m/Y') }}</td>
        <td>
          @if($h->status === 'telat')
            <span class="status-badge status-telat">
              Telat {{ $h->telat_menit }} menit
            </span>
          @else
            <span class="status-badge status-ontime">
              Sesuai Jadwal
            </span>
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>


{{-- ======================= TANDA TANGAN ======================= --}}
<div class="ttd-wrapper">
  <div class="ttd">
    <p>Cirebon, {{ now()->translatedFormat('d F Y') }}</p>
    <p class="jabatan">Kepala Dinas Komunikasi, Informatika dan Statistik</p>
    <p class="nama">________________________</p>
    <p class="nama">Ma'ruf Nuryasa, A.P., M.M.</p>
  </div>
</div>


<footer>
  Laporan ini dicetak otomatis oleh Sistem Apel Pagi DKIS – Kota Cirebon
</footer>

</body>
</html>
