<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>QR Code Rapat DKIS</title>
  <style>
    @page {
      margin: 40px;
    }
    body {
      font-family: 'Segoe UI', Tahoma, sans-serif;
      background: linear-gradient(180deg, #f8fafc 0%, #eef2ff 100%);
      color: #1e293b;
      font-size: 13px;
      line-height: 1.5;
    }
    .header {
      text-align: center;
      border-bottom: 3px solid #3b82f6;
      padding-bottom: 10px;
      margin-bottom: 25px;
    }
    .header img {
      height: 60px;
      margin-bottom: 5px;
    }
    .header h2 {
      margin: 0;
      font-size: 20px;
      color: #1e3a8a;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .header small {
      color: #64748b;
      font-size: 11px;
    }
    .details {
      margin-bottom: 30px;
      background: #f1f5f9;
      border-radius: 10px;
      padding: 15px 20px;
      box-shadow: inset 0 0 6px rgba(0,0,0,0.08);
    }
    .details p {
      margin: 5px 0;
      font-size: 13px;
    }
    .details strong {
      color: #0f172a;
    }
    .qr-container {
      text-align: center;
      margin: 30px 0;
    }
    .qr-container img {
      width: 200px;
      height: 200px;
      border: 6px solid #1e3a8a;
      border-radius: 16px;
      padding: 10px;
      background: #fff;
      box-shadow: 0 0 20px rgba(59, 130, 246, 0.3);
    }
    .qr-container p {
      margin-top: 10px;
      font-weight: 600;
      color: #1e293b;
    }
    h3.section-title {
      text-align: center;
      background: linear-gradient(90deg, #3b82f6, #06b6d4);
      color: #fff;
      border-radius: 10px;
      padding: 8px;
      margin: 25px 0 15px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    ol.steps {
      margin: 0;
      padding-left: 20px;
    }
    ol.steps li {
      margin-bottom: 10px;
      padding-left: 5px;
    }
    ol.steps li strong {
      color: #0f766e;
    }
    .footer {
      text-align: center;
      margin-top: 40px;
      font-size: 11px;
      color: #475569;
      border-top: 1px solid #cbd5e1;
      padding-top: 10px;
    }
  </style>
</head>
<body>
  <div class="header">
    <img src="{{ public_path('img/logo.png') }}" alt="Logo DKIS">
    <h2>Buku Tamu Digital DKIS</h2>
    <small>Dinas Komunikasi, Informatika dan Statistik Kota Cirebon</small>
  </div>

  <div class="details">
    <p><strong>Judul Rapat:</strong> {{ $rapat->judul }}</p>
    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($rapat->waktu_mulai)->format('d/m/Y H:i') }}
      s/d {{ \Carbon\Carbon::parse($rapat->waktu_selesai)->format('d/m/Y H:i') }}</p>
    <p><strong>Lokasi:</strong> {{ $rapat->lokasi ?? '-' }}</p>
  </div>

  <div class="qr-container">
    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code Check-in">
    <p>Scan QR ini untuk melakukan <strong>Check-in Kehadiran</strong></p>
  </div>

  <h3 class="section-title">Langkah-langkah Check-in</h3>
  <ol class="steps">
    <li>Buka <strong>kamera</strong> atau aplikasi <strong>QR Scanner</strong> di smartphone Anda.</li>
    <li>Arahkan kamera ke QR Code di atas.</li>
    <li>Ikuti tautan yang muncul untuk membuka <strong>form check-in rapat</strong>.</li>
    <li>Isi data diri sesuai form (Nama, Email, Instansi, Jabatan).</li>
    <li>Pastikan lokasi Anda berada di area <strong>radius rapat</strong>.</li>
    <li>Tekan tombol <strong>Check-in Sekarang</strong> dan tunggu konfirmasi berhasil.</li>
  </ol>

  <div class="footer">
    <em>Dicetak otomatis oleh sistem Buku Tamu Digital DKIS</em><br>
    {{ now()->format('d/m/Y H:i') }}
  </div>
</body>
</html>
