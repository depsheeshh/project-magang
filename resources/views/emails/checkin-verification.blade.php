@component('mail::message')
# Verifikasi Check-in Rapat

Halo {{ $undangan->nama ?? $undangan->user->name }},

Terima kasih sudah melakukan check-in untuk rapat **{{ $rapat->judul }}**.

Klik tombol berikut untuk menyelesaikan proses check-in:

@component('mail::button', ['url' => $url])
Verifikasi Kehadiran
@endcomponent

> Link ini hanya berlaku sekali. Jika sudah diklik, link tidak bisa digunakan lagi.

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
