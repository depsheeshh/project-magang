# 📖 Buku Tamu Digital

**Buku Tamu Digital** adalah aplikasi berbasis web untuk pencatatan kunjungan tamu secara modern, cepat, dan aman. Aplikasi ini membantu instansi, perusahaan, atau organisasi dalam memantau tamu, frontliner, dan pegawai dengan integrasi **QR Code**, notifikasi real‑time, serta laporan otomatis.

---

## ✨ Fitur Utama
- **Login Multi‑Role**: Admin, Frontliner, Pegawai, dan Tamu dengan akses berbeda.  
- **Scan QR Code**: Tamu cukup scan barcode untuk mengisi data kunjungan.  
- **Approval Frontliner**: Frontliner memverifikasi tujuan tamu sebelum masuk.  
- **Dashboard**: Statistik kunjungan, status tamu, dan laporan otomatis.  
- **Laporan & Status**: Pantau status kunjungan (sedang bertamu, selesai, ditolak).  
- **Notifikasi Real‑Time**: Memberi tahu pegawai terkait adanya tamu.  
- **Responsif & Mobile‑Friendly**: Tampilan optimal di desktop maupun smartphone.  

---

## 🛠️ Teknologi yang Digunakan
- **Laravel** (Backend Framework)  
- **Blade Template** (Frontend Templating)  
- **Bootstrap 5** (UI & Responsif)  
- **AJAX** (Interaksi dinamis tanpa reload)  
- **MySQL / MariaDB** (Database)  

---

## 🚀 Instalasi & Setup

### 1. Clone Repository
```bash
git clone https://github.com/depsheeshh/buku-tamu-digital.git
cd buku-tamu-digital
```

Atau download ZIP lalu ekstrak.

### 2. Install Dependencies
```bash
composer install
npm install && npm run dev
```

### 3. Konfigurasi Environment
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Lalu sesuaikan:
- Database (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)  
- App URL (`APP_URL=http://localhost:8000`)  

### 4. Generate Key
```bash
php artisan key:generate
```

### 5. Migrasi & Seed Database
```bash
php artisan migrate --seed
```

Seeder akan membuat akun default (misalnya admin).

### 6. Jalankan Server
```bash
php artisan serve
```

Akses di browser: [http://localhost:8000](http://localhost:8000)

---

## 👥 Cara Menggunakan Aplikasi
1. **Login** menggunakan akun sesuai role (Admin/Frontliner/Pegawai/Tamu).  
2. **Admin**: mengelola data, melihat laporan, mengatur user.  
3. **Frontliner**: memverifikasi tamu, melakukan check‑in/out manual.  
4. **Pegawai**: menerima notifikasi jika ada tamu yang datang.  
5. **Tamu**: scan QR Code → isi form → tunggu verifikasi frontliner.  
6. **Dashboard** menampilkan status kunjungan dan laporan statistik.  

---

## 📂 Struktur Direktori (Singkat)
```
app/            -> Logic aplikasi (Controllers, Models)
resources/views -> Blade templates (UI)
public/         -> Assets (CSS, JS, Images)
routes/web.php  -> Routing aplikasi
database/       -> Migrations & Seeders
```

---

## 🤝 Kontribusi
1. Fork repository ini.  
2. Buat branch baru (`git checkout -b fitur-baru`).  
3. Commit perubahan (`git commit -m 'Tambah fitur baru'`).  
4. Push ke branch (`git push origin fitur-baru`).  
5. Buat Pull Request.  

---

## 📜 Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).
