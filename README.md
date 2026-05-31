# PustakaUPA — Sistem Informasi Perpustakaan UPA UNEJ

PustakaUPA adalah sistem informasi berbasis web yang dirancang untuk mendigitalkan proses peminjaman dan pengembalian buku di Perpustakaan Unit Pelaksana Akademik (UPA) Universitas Jember. Sistem ini memungkinkan mahasiswa untuk mengajukan peminjaman buku secara online, memantau status peminjaman, dan memproses pengembalian — sekaligus memudahkan admin dalam mengelola seluruh data perpustakaan.

Dibuat oleh: Chelsea Brilliant Syah Putra - 242410101079  
Mata Kuliah: Pemrograman Berbasis Web

\---

## Fitur Utama

### Fitur Customer (Mahasiswa):

* Registrasi \& Login — Daftar akun sebagai customer dan login ke sistem
* Katalog Buku — Lihat seluruh koleksi buku beserta status ketersediaan dan stok secara real-time
* Peminjaman Buku — Ajukan peminjaman dengan memilih buku dari dropdown pencarian AJAX, tentukan tanggal pinjam dan kembali
* Bukti Peminjaman — Halaman detail peminjaman yang bisa ditunjukkan ke petugas perpustakaan untuk mengambil buku fisik
* Pengembalian Buku — Proses pengembalian buku dengan konfirmasi checklist, sistem otomatis menghitung denda keterlambatan
* Riwayat Peminjaman — Pantau semua riwayat peminjaman beserta status dan informasi denda
* Profil — Lihat informasi akun dan statistik peminjaman, serta edit nama, email, dan password

### Fitur Admin:

* Dashboard — Ringkasan statistik data perpustakaan secara real-time (total buku, peminjaman aktif, terlambat, dll)
* Manajemen Buku — CRUD lengkap koleksi buku (tambah, edit, hapus). Stok otomatis berkurang saat dipinjam dan bertambah saat dikembalikan
* Manajemen Peminjaman — Kelola semua data peminjaman dari seluruh customer
* Manajemen Pengembalian — Proses pengembalian buku dan tandai denda yang sudah dibayar
* Live Search — Pencarian data peminjaman secara real-time tanpa reload halaman
* Profil — Lihat dan edit informasi akun admin

\---

## Penerapan Poin RTM

### 1\. HTML \& CSS

* Struktur halaman menggunakan HTML5 semantik dengan Blade template engine Laravel
* Styling menggunakan custom CSS (`public/css/style.css`) dengan CSS Variables untuk konsistensi warna dan tema
* Layout responsif yang menyesuaikan tampilan di berbagai ukuran layar
* Komponen UI: hero section, stat cards, tabel data, form input, flash message, navbar, footer

### 2\. JavaScript \& DOM Manipulation

Penerapan JavaScript dan manipulasi DOM tersebar di beberapa halaman:

* Validasi form sisi klien — validasi input sebelum dikirim ke server (login, register, form peminjaman)
* Dropdown AJAX — hasil pencarian buku dirender langsung ke DOM sebagai dropdown interaktif saat membuat/edit peminjaman
* Render tabel dinamis — hasil live search dirender ke DOM sebagai tabel tanpa reload halaman
* Checklist interaktif — tombol konfirmasi pengembalian hanya aktif jika semua checkbox dicentang (DOM manipulation)
* Validasi konfirmasi password real-time — pengecekan kecocokan password langsung saat mengetik di halaman edit profil
* Scroll to top button — tombol muncul/hilang otomatis berdasarkan posisi scroll
* Auto-dismiss flash message — pesan notifikasi menghilang otomatis setelah 4 detik
* Konfirmasi hapus — dialog `confirm()` sebelum menghapus data

### 3\. PHP \& CRUD Database

Tiga entitas utama dengan operasi CRUD menggunakan Laravel Eloquent ORM:

|Entitas|Create|Read|Update|Delete|
|-|-|-|-|-|
|buku|Admin|Semua|Admin|Admin|
|peminjaman|Admin \& Customer|Admin \& Customer|Admin \& Customer|Admin \& Customer|
|users|—|Admin \& Customer|Admin \& Customer|—|



### 4\. Cookie \& Session

* Session Laravel — digunakan untuk menjaga status autentikasi pengguna dan proteksi route dengan middleware `auth` dan `cek.admin`
* Cookie — nama pengguna disimpan ke cookie selama 30 hari saat login/register. Cookie ini digunakan untuk menampilkan pesan *"Selamat datang kembali, \[nama]!"* di halaman beranda meski session sudah habis. Cookie juga diperbarui otomatis saat pengguna mengganti nama di halaman profil

### 5\. Komunikasi Asinkronus AJAX \& JSON

Terdapat tiga titik implementasi AJAX menggunakan Fetch API dengan response JSON:

|Endpoint|Fungsi|
|-|-|
|`GET /peminjaman-search?keyword=`|Live search data peminjaman di beranda dan halaman peminjaman|
|`GET /buku-cari?keyword=`|Cari buku real-time untuk dropdown saat membuat/edit peminjaman|
|`GET /pengembalian/{id}/cek`|Cek status keterlambatan dan estimasi denda secara real-time|

\---

## Cara Instalasi \& Menjalankan

### Persyaratan Sistem

* PHP >= 8.2
* Composer
* XAMPP / Laragon (MySQL \& Apache)

### Langkah-langkah

**1. Ekstrak atau clone proyek ke folder htdocs/laragon**

**2. Install dependencies**
composer install



**3. Salin file konfigurasi**
cp .env.example .env



**4. Sesuaikan konfigurasi database di `.env`**
APP\\\_NAME="PustakaUPA"
APP\\\_TIMEZONE=Asia/Jakarta
DB\\\_CONNECTION=mysql
DB\\\_HOST=127.0.0.1
DB\\\_PORT=3306
DB\\\_DATABASE=perpustakaan\\\_pweb
DB\\\_USERNAME=root
DB\\\_PASSWORD=



**5. Generate application key**
php artisan key:generate



**6. Jalankan migration \& seeder**
php artisan migrate --seed



**7. Buat symbolic link storage**
php artisan storage:link



**8. Jalankan server**
php artisan serve



Buka browser: **http://localhost:8000**

\---

## Akun Default

|Role|Email|Password|
|-|-|-|
|Admin|admin@perpustakaan.ac.id|admin123|
|Customer|Daftar sendiri via `/register`|—|

> Admin hanya 1 dan tidak bisa didaftarkan lewat halaman register. Register hanya untuk Customer.

\---

