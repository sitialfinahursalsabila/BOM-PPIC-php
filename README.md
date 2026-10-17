# SI BOM & PPIC - Modul 7 (Laravel + MySQL di Laragon)

Functional Core System untuk proyek Sistem Informasi Pengelolaan BOM dan Perencanaan Produksi.
Folder `overlay` adalah lapisan aplikasi yang ditempel ke project Laravel baru (folder `vendor` dibuat Composer di komputermu).

## Fitur dan dasarnya
| Fitur | Use case / aturan | Lokasi kode |
|---|---|---|
| Login, logout, session, proteksi halaman | NFR-01 | AuthController, route group `auth` |
| Otorisasi per peran | BR-05 | PeranMiddleware, Pengguna::punyaPeran |
| CRUD master item + proteksi hapus | UC-01, BR-01 | ItemController |
| Tampil BOM aktif, struktur berjenjang, riwayat | UC-03/04 (baca) | BomController |
| **Core module: ubah status produksi** | UC-07, BR-08, BR-09 | StatusProduksiService |
| Validasi kelengkapan BOM | UC-11 | KelengkapanBom |
| Hitung kebutuhan bahan, tandai bahan kurang | BR-06, BR-10 | KebutuhanBahan |
| Dashboard per peran | Modul 6 H-02 | DashboardController |

Belum dibuat (Modul 8): form penyusunan BOM (UC-02), buat rencana produksi, laporan (UC-09), estimasi biaya (UC-10).

## Pasang otomatis di Laragon (disarankan)
1. Ekstrak zip ke `C:\laragon\www\` (hasilnya folder `sistem-bom-ppic-laragon` berisi `pasang-laragon.bat` dan `overlay`).
2. Buka Laragon, klik **Start All** (Apache/Nginx dan MySQL harus hidup).
3. Klik kanan ikon Laragon > **Menu > Terminal**, lalu:
   ```
   cd www\sistem-bom-ppic-laragon
   pasang-laragon.bat
   ```
Skrip membuat project Laravel di `C:\laragon\www\sistem-bom-ppic`, menyalin kode, mengisi `.env` untuk MySQL Laragon, membuat database `bom_ppic`, menjalankan `migrate:fresh --seed`, lalu membuka http://127.0.0.1:8000. Skrip aman dijalankan ulang (data dikembalikan ke data contoh).

## Pasang manual
```
cd C:\laragon\www
composer create-project laravel/laravel sistem-bom-ppic
```
1. Salin seluruh isi folder `overlay` ke `sistem-bom-ppic` (pilih Replace).
2. Salin `.env.laragon` menjadi `.env` (timpa).
3. Buat database kosong `bom_ppic` (HeidiSQL di Laragon, atau `mysql -u root -e "CREATE DATABASE bom_ppic"`).
4. Jalankan:
   ```
   cd sistem-bom-ppic
   php artisan key:generate
   php artisan migrate:fresh --seed
   php artisan serve
   ```
5. Buka http://127.0.0.1:8000 (atau http://sistem-bom-ppic.test bila virtual host Laragon aktif; Menu > Apache > Reload).
6. Test (SQLite memori, tidak menyentuh MySQL): `php artisan test`

Prasyarat: PHP 8.2+ dengan ekstensi pdo_mysql, mbstring, openssl, xml, fileinfo (pdo_sqlite untuk test). Semuanya sudah ada di Laragon. Node.js tidak diperlukan.

## Akun contoh (kata sandi semua: `password123`)
| Username | Peran |
|---|---|
| rina.ppic | Staff PPIC |
| budi.produksi | Staff Produksi |
| dewi.gudang | Staff Gudang |
| andi.manajer | Manajer Produksi |
| hendra.direktur | Direktur |

Ganti kata sandi sebelum aplikasi dipakai di luar praktikum.

## Skenario demo UC-07
1. Login `budi.produksi`, buka Status Produksi.
2. RPP005 (Draft): Action, Mulai Produksi, konfirmasi. Status menjadi Diproses dan baris log baru tercatat.
3. RPP003 (Diproses): Action, Tandai Selesai. BOM004 lengkap, jadi status menjadi Selesai.
4. Penolakan BR-09: RPP005 sudah Diproses dari langkah 2 (BOM006). Lewat `php artisan tinker` tambahkan sub assembly tanpa anak, lalu coba Tandai Selesai:
   ```
   DB::table('detail_bom')->insert(['id_detail_bom'=>'DBM099','id_bom'=>'BOM006','id_item'=>'ITM010','id_induk'=>null,'jumlah'=>1,'level'=>'Sub Assembly']);
   ```
   Pesan penolakan tampil dan status tetap Diproses. Hapus baris itu setelahnya: `DB::table('detail_bom')->where('id_detail_bom','DBM099')->delete();`
5. Penolakan BR-08 ada pada test `test_draft_langsung_ke_selesai_ditolak_br08` (menu Action sengaja tidak menampilkan lompatan status).

## Catatan database
- Struktur tabel, nama kolom, tipe data, dan data contoh sama dengan Modul 5.
- Aturan "satu BOM aktif per produk" (BR-03) dijaga di aplikasi, bukan trigger. Aturan ini wajib dibuat saat form penyusunan BOM (UC-02) dibangun di Modul 8.
- Migrasi `..._000009_tambah_check_constraint` memasang CHECK constraint. Bila versi MySQL/MariaDB menolaknya, migrasi melanjutkan dan aplikasi tetap memvalidasi nilai yang sama di server.
- Untuk memakai TiDB Cloud lagi, cukup ganti nilai `DB_HOST`, `DB_PORT=4000`, `DB_USERNAME`, `DB_PASSWORD` di `.env` dan tambahkan `MYSQL_ATTR_SSL_CA` berisi path sertifikat CA. Kode tidak perlu diubah.

## Asumsi yang perlu dikonfirmasi
- Definisi "BOM lengkap" (UC-11): minimal satu komponen, jumlah > 0, item terdaftar, induk valid, setiap sub assembly punya anak. Detailnya ada di komentar `KelengkapanBom.php`.
- Hanya Staff Produksi yang boleh mengubah status. Peran lain hanya melihat.
- Hak akses Master Item hanya Staff PPIC.

## Peta bukti uji untuk LKM
| LKM | Cara menghasilkan bukti |
|---|---|
| LKM 1 | `php -v`, `composer -V`, `php artisan --version`, `git --version`, versi database (`SELECT VERSION();`) |
| LKM 2 | `php artisan migrate --seed` berhasil, lalu cek data lewat aplikasi (Read), tambah item (Create), ubah item (Update), hapus item baru (Delete) |
| LKM 3 | `AuthTest` (login benar, password salah, halaman protected, logout), plus screenshot manual |
| LKM 4 | `ItemCrudTest` dan screenshot daftar, form, pesan validasi, pesan gagal hapus |
| LKM 5 | `StatusProduksiTest`, screenshot dialog, pesan sukses, pesan penolakan, tabel log |

## Masalah umum
- *Halaman putih atau "could not find driver"*: aktifkan ekstensi `pdo_mysql` (Laragon > Menu > PHP > Extensions).
- *SQLSTATE[HY000] [2002] Connection refused*: MySQL belum Start di Laragon.
- *Unknown database 'bom_ppic'*: buat database kosong `bom_ppic`, lalu `php artisan migrate:fresh --seed`.
- *Access denied for user 'root'*: password root MySQL kamu bukan kosong; isi `DB_PASSWORD` di `.env`, lalu `php artisan config:clear`.
- *419 Page Expired*: bersihkan cookie browser untuk 127.0.0.1 / sistem-bom-ppic.test, pastikan `SESSION_DRIVER=file` di `.env`.
- *Login gagal padahal password benar*: jalankan `php artisan migrate:fresh --seed` untuk memuat ulang akun contoh, lalu `php artisan config:clear`.
"# BOM-PPIC-php" 
