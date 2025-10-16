<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapan Kunjungan</title>
    <style>
        /* ====== GLOBAL ====== */
        body {
            font-family: "DejaVu Sans", Arial, sans-serif;
            font-size: 12px;
            color: #212529;
            line-height: 1.6;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4 portrait;
            margin: 100px 40px 80px 40px;
        }

        /* ====== HEADER ====== */
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

        .kop h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .kop h2 {
            font-size: 14px;
            font-weight: normal;
            margin-top: 0;
            text-transform: uppercase;
        }

        .kop p {
            font-size: 11px;
            margin: 1px 0;
        }

        .garis-tebal {
            border-bottom: 3px double #000;
            margin-top: 8px;
        }

        /* ====== JUDUL LAPORAN ====== */
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

        /* ====== TABEL DATA ====== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #b5b5b5;
            padding: 8px 6px;
            font-size: 11px;
        }

        th {
            background-color: #f2f3f5;
            font-weight: 700;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        td {
            vertical-align: top;
        }

        /* ====== STATUS BADGE ====== */
        .status {
            display: inline-block;
            padding: 3px 8px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: capitalize;
        }

        .status-menunggu {
            background-color: #fff7cc;
            color: #7a6300;
            border: 1px solid #ffe58a;
        }

        .status-sedang_bertamu {
            background-color: #d6eaff;
            color: #004085;
            border: 1px solid #9ec5fe;
        }

        .status-selesai {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* ====== TANDA TANGAN ====== */
        .ttd-wrapper {
            page-break-inside: avoid;
            margin-top: 40px;
        }

        .ttd {
            width: 260px;
            float: right;
            text-align: center;
            font-size: 12px;
        }

        .ttd p {
            margin: 3px 0;
        }

        .ttd .nama {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd .jabatan {
            font-size: 11px;
        }

        /* ====== FOOTER ====== */
        footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            text-align: right;
            font-size: 10px;
            color: #6c757d;
        }

        .page-number:after {
            content: counter(page) " / " counter(pages);
        }

        /* ====== DICETAK PADA ====== */
        .dicetak {
            position: fixed;
            bottom: 45px;
            left: 0;
            right: 0;
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
            <p>Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134</p>
            <p>Telepon: (0231) 123456 &nbsp; | &nbsp; Email: diskominfo@cirebonkota.go.id</p>
            <div class="garis-tebal"></div>
        </div>
    </header>

    <h2 class="judul">Laporan Rekapan Kunjungan</h2>
    <div class="subtitle">
        @if(request('start_date') && request('end_date'))
            Periode: {{ request('start_date') }} s/d {{ request('end_date') }}
        @else
            Periode: Semua
        @endif
        <br>
        @if(request('status'))
            Status: {{ ucfirst(str_replace('_',' ', request('status'))) }}
        @else
            Status: Semua
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tamu</th>
                <th>Pegawai Tujuan</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kunjungan as $i => $k)
                <tr>
                    <td style="text-align:center;">{{ $i+1 }}</td>
                    <td>{{ $k->tamu?->nama ?? $k->tamu?->user?->name ?? '-' }}</td>
                    <td>{{ $k->pegawai?->user?->name ?? '-' }}</td>
                    <td>{{ $k->keperluan }}</td>
                    <td style="text-align:center;">
                        <span class="status status-{{ $k->status }}">
                            {{ str_replace('_',' ',$k->status) }}
                        </span>
                    </td>
                    <td style="text-align:center;">{{ $k->waktu_masuk }}</td>
                    <td style="text-align:center;">{{ $k->waktu_keluar ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:10px;">Tidak ada data kunjungan</td>
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
