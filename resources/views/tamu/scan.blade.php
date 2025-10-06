@extends('layouts.app')

@section('title','Scan QR Code')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center mt-5" style="min-height: 80vh;">

  <h2 class="mb-2 mt-5 fw-bold">📷 Scan QR Code</h2>
  <p class="text-muted mb-4">Arahkan kamera Anda ke QR Code yang tersedia</p>

  {{-- Placeholder untuk scanner --}}
  <div id="reader" style="width: 320px; max-width: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.15);"></div>

  {{-- Tombol tambahan --}}
  <div class="mt-4 d-flex gap-3">
      <button class="btn btn-dark" id="flash-btn">🔦 Flash</button>
      <button class="btn btn-secondary" onclick="window.history.back()">❌ Batal</button>
  </div>
</div>

{{-- Script scanner --}}
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
  function onScanSuccess(decodedText, decodedResult) {
      // Simpan flag ke server via fetch
    fetch("{{ route('tamu.scan.success') }}", {
    method: "POST",
    headers: {
        "X-CSRF-TOKEN": "{{ csrf_token() }}",
        "Content-Type": "application/json"
    },
    body: JSON.stringify({ qr: decodedText })
    })
    .then(res => {
        if (!res.ok) throw new Error("Gagal menyimpan sesi scan");
        return res.json();
    })
    .then(data => {
        if (data.status === "ok") {
            window.location.href = "{{ route('tamu.form') }}";
        }
    })
    .catch(err => {
        alert("Terjadi kesalahan: " + err.message);
    });
}

  let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 200 });
  html5QrcodeScanner.render(onScanSuccess);
  // contoh toggle flash
    // let isFlashOn = false;
    // document.getElementById("flash-btn").addEventListener("click", () => {
    //     html5QrcodeScanner.getState().then(state => {
    //         if (!isFlashOn) {
    //             html5QrcodeScanner.applyVideoConstraints({ advanced: [{ torch: true }] });
    //         } else {
    //             html5QrcodeScanner.applyVideoConstraints({ advanced: [{ torch: false }] });
    //         }
    //         isFlashOn = !isFlashOn;
    //     });
    // });
    // </script>
@endsection
