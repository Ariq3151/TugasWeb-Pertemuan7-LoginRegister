# Tugas Web Pertemuan 7 — Login & Register

## Deskripsi Proyek

Proyek ini merupakan tugas rutin mata kuliah Pemrograman Web Pertemuan 7 yang dibuat menggunakan PHP Native. Aplikasi ini menerapkan sistem registrasi dan login pengguna dengan penyimpanan data dalam format JSON tanpa menggunakan database MySQL.

Aplikasi ini juga dilengkapi dengan fitur tambahan berupa CSS Styling, Edit Profile, dan Remember Me.

## Fitur Aplikasi

### 1. Register
- Mendaftarkan akun pengguna baru.
- Memvalidasi nama, email, dan password.
- Memeriksa format email menggunakan `filter_var()`.
- Memeriksa email yang sudah terdaftar agar tidak terjadi duplikasi.
- Mengenkripsi password menggunakan `password_hash()`.

### 2. Login
- Memeriksa email dan password pengguna.
- Memverifikasi password menggunakan `password_verify()`.
- Menggunakan session untuk menyimpan status login pengguna.

### 3. Dashboard
- Menampilkan informasi pengguna yang berhasil login.
- Melindungi halaman agar hanya dapat diakses oleh pengguna yang sudah login.

### 4. Edit Profile
- Mengubah nama dan email pengguna.
- Memvalidasi data sebelum disimpan.
- Memeriksa agar email tidak digunakan oleh akun lain.
- Menyimpan perubahan ke file `users.json`.

### 5. Remember Me
- Memungkinkan pengguna mempertahankan status login menggunakan cookie.
- Menggunakan token acak untuk memulihkan session.
- Mengatur masa berlaku token selama 30 hari.
- Menghapus token ketika pengguna melakukan logout.

### 6. Logout
- Menghapus session pengguna.
- Menghapus cookie Remember Me.
- Mencabut token Remember Me yang tersimpan.

### 7. CSS Styling
- Mengatur tampilan halaman agar lebih rapi dan nyaman digunakan.
- Menggunakan file `style.css` untuk mengatur tampilan form dan elemen halaman.

## Teknologi yang Digunakan

- PHP Native
- HTML5
- CSS3
- JSON
- Session PHP
- Cookie PHP
- XAMPP sebagai server lokal

## Struktur File

```text
TugasWeb-Pertemuan7-LoginRegister/
├── register.php
├── login.php
├── dashboard.php
├── edit-profile.php
├── remember.php
├── logout.php
├── users.json
├── style.css
└── README.md
```

## Cara Menjalankan Aplikasi

1. Instal dan jalankan XAMPP.
2. Aktifkan Apache pada XAMPP Control Panel.
3. Letakkan folder proyek di dalam direktori `htdocs`.
4. Pastikan file `users.json` tersedia dan dapat ditulis oleh PHP.
5. Buka browser dan akses alamat berikut:

   `http://localhost/TugasWeb-Pertemuan7-LoginRegister/register.php`

6. Daftarkan akun baru.
7. Login menggunakan akun yang sudah didaftarkan.
8. Coba fitur Dashboard, Edit Profile, Remember Me, dan Logout.

## Penyimpanan Data

Data pengguna disimpan dalam file `users.json`. Password disimpan dalam bentuk hash menggunakan `password_hash()`, bukan sebagai teks biasa.

Contoh isi awal `users.json`:

```json
[]
```

## Catatan

- Aplikasi dijalankan menggunakan server lokal seperti XAMPP karena GitHub Pages tidak mendukung eksekusi PHP.
- Gunakan data contoh saat mengunggah proyek ke GitHub.
- Jangan mengunggah password asli atau token Remember Me aktif ke repository publik.

## Tujuan Pembelajaran

Proyek ini bertujuan untuk memahami penerapan PHP Native dalam pengolahan form, validasi input, penyimpanan data JSON, autentikasi pengguna, session, cookie, serta pengelolaan profil pengguna.

---

**Nama Repository:** `TugasWeb-Pertemuan7-LoginRegister`

**Mata Kuliah:** Pemrograman Web

**Tugas:** Tugas Rutin Pertemuan 7 — Login & Register PHP Native
