@component('mail::message')
# 🔐 Reset Password Buku Tamu Digital

Halo **{{ $user->name ?? 'Pengguna' }}**,

Kami menerima permintaan untuk reset password akun Anda di **Buku Tamu Digital**.
Klik tombol di bawah ini untuk mengatur ulang password Anda:

@component('mail::button', ['url' => $actionUrl])
Reset Password
@endcomponent

Jika Anda tidak meminta reset password, abaikan email ini.
Tautan reset hanya berlaku selama **60 menit**.

---

Terima kasih,
**Tim Buku Tamu Digital**

<hr>
<small>Email ini dikirim otomatis, mohon tidak membalas langsung.</small>
@endcomponent
