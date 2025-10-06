<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #212529;
            line-height: 1.4;
        }
        header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            overflow: auto;
        }
        header img {
            float: left;
            width: 60px;
            height: 60px;
            margin-right: 10px;
        }
        header .kop {
            text-align: center;
        }
        header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        header p {
            margin: 0;
            font-size: 11px;
        }
        h2 {
            text-align: center;
            margin: 0 0 5px 0;
            font-size: 16px;
        }
        .subtitle {
            text-align: center;
            font-size: 11px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        /* Bootstrap-like table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            font-size: 11px;
        }
        th {
            background-color: #f8f9fa;
            font-weight: 600;
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #fdfdfe;
        }

        /* Status badge style */
        .status {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: capitalize;
        }
        .status-menunggu { background-color: #fff3cd; color: #856404; }
        .status-sedang_bertamu { background-color: #cce5ff; color: #004085; }
        .status-selesai { background-color: #d4edda; color: #155724; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }

        /* Footer nomor halaman */
        @page {
            margin: 100px 25px;
        }
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
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('img/logo.png') }}" alt="Logo">
        <div class="kop">
            <h1>Buku Tamu Digital</h1>
            <p>Jl. Brigjend Dharsono No.1, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45135</p>
        </div>
    </header>

    <h2>Laporan Kunjungan</h2>
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
            <br>
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </div>
    <table>
        <thead>
            <tr>
                <th>Nama Tamu</th>
                <th>Pegawai Tujuan</th>
                <th>Keperluan</th>
                <th>Status</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kunjungan as $k)
                <tr>
                    <td>{{ $k->tamu?->nama ?? $k->tamu?->user?->name ?? '-' }}</td>
                    <td>{{ $k->pegawai?->user?->name ?? '-' }}</td>
                    <td>{{ $k->keperluan }}</td>
                    <td><span class="status status-{{ $k->status }}">
                        {{ str_replace('_',' ',$k->status) }}
                    </span></td>
                    <td>{{ $k->waktu_masuk }}</td>
                    <td>{{ $k->waktu_keluar ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:10px;">
                        Tidak ada data kunjungan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <footer>
        Halaman <span class="page-number"></span>
    </footer>
</body>
</html>
