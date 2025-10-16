<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>QR Code Buku Tamu</title>
  <style>
    /* === GLOBAL STYLE === */
    body {
      font-family: "DejaVu Sans", sans-serif;
      margin: 50px;
      text-align: center;
      background: #f9fbff;
      color: #1a1a1a;
      position: relative;
    }

    /* === HEADER === */
    .header {
      margin-bottom: 25px;
      border-bottom: 3px solid #0077ff;
      padding-bottom: 10px;
    }

    .header img {
      max-height: 90px;
      margin-bottom: 10px;
    }

    .title {
      font-size: 24px;
      font-weight: 700;
      text-transform: uppercase;
      color: #004aad;
      margin-bottom: 4px;
      letter-spacing: 0.5px;
    }

    .subtitle {
      font-size: 15px;
      color: #444;
      margin-bottom: 5px;
    }

    /* === QR CONTAINER === */
    .qr-container {
      background: linear-gradient(180deg, #e8f0ff, #ffffff);
      display: inline-block;
      padding: 30px;
      border-radius: 18px;
      border: 2px solid #0077ff;
      box-shadow: 0 8px 25px rgba(0, 102, 255, 0.15);
      margin: 40px 0;
    }

    .qr-container img {
      width: 260px;
      height: 260px;
    }

    /* === LANGKAH-LANGKAH === */
    h4 {
      color: #004aad;
      font-size: 16px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 12px;
    }

    ol {
      display: inline-block;
      text-align: left;
      font-size: 14px;
      color: #333;
      line-height: 1.6;
      background: #f1f5ff;
      padding: 15px 25px;
      border-radius: 10px;
      border-left: 4px solid #0077ff;
      box-shadow: 0 4px 12px rgba(0, 0, 50, 0.05);
    }

    ol li {
      margin-bottom: 6px;
    }

    ol li::marker {
      color: #0077ff;
      font-weight: bold;
    }

    /* === FOOTER === */
    .footer {
      margin-top: 40px;
      font-size: 12px;
      color: #555;
    }

    .footer span {
      display: inline-block;
      padding: 6px 14px;
      background: #0077ff;
      color: #fff;
      border-radius: 30px;
      font-weight: 600;
      letter-spacing: 0.3px;
    }

    /* === WATERMARK === */
    .watermark {
      position: fixed;
      bottom: 40px;
      right: 40px;
      opacity: 0.08;
      font-size: 80px;
      font-weight: bold;
      color: #004aad;
      transform: rotate(-15deg);
      pointer-events: none;
    }
  </style>
</head>
<body>
  <div class="header">
    {{-- Logo perusahaan --}}
    <img src="{{ public_path('img/logo.png') }}" alt="Logo">
    <div class="title">{{ $companyName }}</div>
    <div class="subtitle">Silakan Scan QR Code untuk Mengisi Buku Tamu Digital</div>
  </div>

  <div class="qr-container">
    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
  </div>

  <h4>Langkah-langkah untuk Tamu:</h4>
  <ol>
    <li>Buka aplikasi web Buku Tamu Digital.</li>
    <li>Scan QR Code dengan kamera ponsel Anda.</li>
    <li>Isi data diri, pilih pegawai yang ingin ditemui, dan tuliskan keperluan.</li>
    <li>Tunggu konfirmasi dari frontliner.</li>
    <li>Jika pegawai tidak tersedia, Anda akan mendapat pemberitahuan penolakan.</li>
    <li>Jika pegawai tersedia, Anda dipersilakan masuk dan bertemu pegawai tersebut.</li>
    <li>Setelah selesai bertamu, tekan tombol <b>“Selesai”</b> di aplikasi.</li>
  </ol>

  <div class="footer">
    <span>DKIS Kota Cirebon</span>
    <p>Inovasi Layanan Publik Digital • {{ now()->translatedFormat('F Y') }}</p>
  </div>

  <div class="watermark">{{ strtoupper(Str::limit("DKIS CIREBON", 12, '')) }}</div>
</body>
</html>
