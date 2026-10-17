<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/** Data contoh yang sama dengan LKM 5 Modul 5. Kata sandi semua akun: password123 */
class BomPpicSeeder extends Seeder
{
    public function run(): void
    {
        $hash = Hash::make('password123');

        DB::table('pengguna')->insert([
            ['id_pengguna' => 'USR01', 'nama' => 'Rina Puspita', 'username' => 'rina.ppic', 'password_hash' => $hash, 'peran' => 'Staff PPIC'],
            ['id_pengguna' => 'USR02', 'nama' => 'Budi Santoso', 'username' => 'budi.produksi', 'password_hash' => $hash, 'peran' => 'Staff Produksi'],
            ['id_pengguna' => 'USR03', 'nama' => 'Dewi Lestari', 'username' => 'dewi.gudang', 'password_hash' => $hash, 'peran' => 'Staff Gudang'],
            ['id_pengguna' => 'USR04', 'nama' => 'Andi Wijaya', 'username' => 'andi.manajer', 'password_hash' => $hash, 'peran' => 'Manajer Produksi'],
            ['id_pengguna' => 'USR05', 'nama' => 'Hendra Kusuma', 'username' => 'hendra.direktur', 'password_hash' => $hash, 'peran' => 'Direktur'],
        ]);

        DB::table('item')->insert([
            ['id_item' => 'ITM001', 'kode_item' => 'MDF01', 'deskripsi' => 'Papan MDF 18 mm', 'satuan' => 'lembar', 'harga_satuan' => 185000, 'stok' => 40],
            ['id_item' => 'ITM002', 'kode_item' => 'BSI01', 'deskripsi' => 'Besi hollow 4x4', 'satuan' => 'batang', 'harga_satuan' => 45000, 'stok' => 120],
            ['id_item' => 'ITM003', 'kode_item' => 'SKR01', 'deskripsi' => 'Sekrup 4 cm', 'satuan' => 'pcs', 'harga_satuan' => 200, 'stok' => 5000],
            ['id_item' => 'ITM004', 'kode_item' => 'ENG01', 'deskripsi' => 'Engsel pintu', 'satuan' => 'pcs', 'harga_satuan' => 8000, 'stok' => 60],
            ['id_item' => 'ITM005', 'kode_item' => 'RDA01', 'deskripsi' => 'Roda kursi', 'satuan' => 'pcs', 'harga_satuan' => 15000, 'stok' => 80],
            ['id_item' => 'ITM006', 'kode_item' => 'BSA01', 'deskripsi' => 'Busa dudukan', 'satuan' => 'pcs', 'harga_satuan' => 35000, 'stok' => 25],
            ['id_item' => 'ITM007', 'kode_item' => 'KNP01', 'deskripsi' => 'Kain pelapis', 'satuan' => 'meter', 'harga_satuan' => 30000, 'stok' => 50],
            ['id_item' => 'ITM008', 'kode_item' => 'GGP01', 'deskripsi' => 'Gagang pintu', 'satuan' => 'pcs', 'harga_satuan' => 6000, 'stok' => 100],
            ['id_item' => 'ITM009', 'kode_item' => 'CAT01', 'deskripsi' => 'Cat kayu', 'satuan' => 'kaleng', 'harga_satuan' => 55000, 'stok' => 12],
            ['id_item' => 'ITM010', 'kode_item' => 'RGT01', 'deskripsi' => 'Rangka besi meja', 'satuan' => 'unit', 'harga_satuan' => 90000, 'stok' => 20],
        ]);

        DB::table('produk')->insert([
            ['id_produk' => 'PRD001', 'kode_produk' => 'MK100', 'nama_produk' => 'Meja Kerja'],
            ['id_produk' => 'PRD002', 'kode_produk' => 'KK200', 'nama_produk' => 'Kursi Kantor'],
            ['id_produk' => 'PRD003', 'kode_produk' => 'LP300', 'nama_produk' => 'Lemari Pakaian'],
            ['id_produk' => 'PRD004', 'kode_produk' => 'RB400', 'nama_produk' => 'Rak Buku'],
            ['id_produk' => 'PRD005', 'kode_produk' => 'MM500', 'nama_produk' => 'Meja Makan'],
        ]);

        DB::table('bom')->insert([
            ['id_bom' => 'BOM001', 'id_produk' => 'PRD001', 'versi' => 1, 'status' => 'Nonaktif', 'tanggal_dibuat' => '2026-08-05'],
            ['id_bom' => 'BOM002', 'id_produk' => 'PRD001', 'versi' => 2, 'status' => 'Aktif', 'tanggal_dibuat' => '2026-08-20'],
            ['id_bom' => 'BOM003', 'id_produk' => 'PRD002', 'versi' => 1, 'status' => 'Aktif', 'tanggal_dibuat' => '2026-08-10'],
            ['id_bom' => 'BOM004', 'id_produk' => 'PRD003', 'versi' => 1, 'status' => 'Aktif', 'tanggal_dibuat' => '2026-08-12'],
            ['id_bom' => 'BOM005', 'id_produk' => 'PRD004', 'versi' => 1, 'status' => 'Aktif', 'tanggal_dibuat' => '2026-08-14'],
            ['id_bom' => 'BOM006', 'id_produk' => 'PRD005', 'versi' => 1, 'status' => 'Aktif', 'tanggal_dibuat' => '2026-08-18'],
        ]);

        // Induk (DBM004) harus lebih dulu daripada anaknya (DBM005) karena foreign key id_induk.
        DB::table('detail_bom')->insert([
            ['id_detail_bom' => 'DBM001', 'id_bom' => 'BOM001', 'id_item' => 'ITM001', 'id_induk' => null, 'jumlah' => 2, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM002', 'id_bom' => 'BOM001', 'id_item' => 'ITM002', 'id_induk' => null, 'jumlah' => 3, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM003', 'id_bom' => 'BOM002', 'id_item' => 'ITM001', 'id_induk' => null, 'jumlah' => 2, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM004', 'id_bom' => 'BOM002', 'id_item' => 'ITM010', 'id_induk' => null, 'jumlah' => 1, 'level' => 'Sub Assembly'],
            ['id_detail_bom' => 'DBM005', 'id_bom' => 'BOM002', 'id_item' => 'ITM003', 'id_induk' => 'DBM004', 'jumlah' => 8, 'level' => 'Komponen Kecil'],
            ['id_detail_bom' => 'DBM006', 'id_bom' => 'BOM002', 'id_item' => 'ITM009', 'id_induk' => null, 'jumlah' => 1, 'level' => 'Komponen Kecil'],
            ['id_detail_bom' => 'DBM007', 'id_bom' => 'BOM003', 'id_item' => 'ITM006', 'id_induk' => null, 'jumlah' => 1, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM008', 'id_bom' => 'BOM003', 'id_item' => 'ITM007', 'id_induk' => null, 'jumlah' => 1.5, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM009', 'id_bom' => 'BOM003', 'id_item' => 'ITM005', 'id_induk' => null, 'jumlah' => 5, 'level' => 'Komponen Kecil'],
            ['id_detail_bom' => 'DBM010', 'id_bom' => 'BOM003', 'id_item' => 'ITM003', 'id_induk' => null, 'jumlah' => 6, 'level' => 'Komponen Kecil'],
            ['id_detail_bom' => 'DBM011', 'id_bom' => 'BOM004', 'id_item' => 'ITM001', 'id_induk' => null, 'jumlah' => 6, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM012', 'id_bom' => 'BOM004', 'id_item' => 'ITM004', 'id_induk' => null, 'jumlah' => 4, 'level' => 'Komponen Kecil'],
            ['id_detail_bom' => 'DBM013', 'id_bom' => 'BOM004', 'id_item' => 'ITM008', 'id_induk' => null, 'jumlah' => 2, 'level' => 'Komponen Kecil'],
            ['id_detail_bom' => 'DBM014', 'id_bom' => 'BOM005', 'id_item' => 'ITM001', 'id_induk' => null, 'jumlah' => 4, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM015', 'id_bom' => 'BOM005', 'id_item' => 'ITM003', 'id_induk' => null, 'jumlah' => 16, 'level' => 'Komponen Kecil'],
            ['id_detail_bom' => 'DBM016', 'id_bom' => 'BOM006', 'id_item' => 'ITM001', 'id_induk' => null, 'jumlah' => 3, 'level' => 'Bahan Utama'],
            ['id_detail_bom' => 'DBM017', 'id_bom' => 'BOM006', 'id_item' => 'ITM002', 'id_induk' => null, 'jumlah' => 4, 'level' => 'Bahan Utama'],
        ]);

        DB::table('riwayat_bom')->insert([
            ['id_riwayat' => 'RWY001', 'id_bom' => 'BOM001', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-05 09:00:00', 'keterangan' => 'BOM Meja Kerja versi 1 dibuat'],
            ['id_riwayat' => 'RWY002', 'id_bom' => 'BOM003', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-10 09:30:00', 'keterangan' => 'BOM Kursi Kantor versi 1 dibuat'],
            ['id_riwayat' => 'RWY003', 'id_bom' => 'BOM004', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-12 10:00:00', 'keterangan' => 'BOM Lemari Pakaian versi 1 dibuat'],
            ['id_riwayat' => 'RWY004', 'id_bom' => 'BOM005', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-14 10:15:00', 'keterangan' => 'BOM Rak Buku versi 1 dibuat'],
            ['id_riwayat' => 'RWY005', 'id_bom' => 'BOM006', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-18 08:45:00', 'keterangan' => 'BOM Meja Makan versi 1 dibuat'],
            ['id_riwayat' => 'RWY006', 'id_bom' => 'BOM002', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-20 09:00:00', 'keterangan' => 'Spesifikasi berubah, rangka besi menjadi sub assembly, versi 2 dibuat'],
            ['id_riwayat' => 'RWY007', 'id_bom' => 'BOM001', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-20 09:05:00', 'keterangan' => 'Versi 1 dinonaktifkan karena digantikan versi 2'],
            ['id_riwayat' => 'RWY008', 'id_bom' => 'BOM002', 'id_pengguna' => 'USR01', 'tanggal_perubahan' => '2026-08-22 13:20:00', 'keterangan' => 'Penambahan cat kayu pada struktur BOM'],
        ]);

        DB::table('rencana_produksi')->insert([
            ['id_rencana' => 'RPP001', 'id_bom' => 'BOM002', 'id_pengguna' => 'USR01', 'jumlah_rencana' => 10, 'tanggal_rencana' => '2026-09-01', 'status' => 'Selesai'],
            ['id_rencana' => 'RPP002', 'id_bom' => 'BOM003', 'id_pengguna' => 'USR01', 'jumlah_rencana' => 20, 'tanggal_rencana' => '2026-09-03', 'status' => 'Selesai'],
            ['id_rencana' => 'RPP003', 'id_bom' => 'BOM004', 'id_pengguna' => 'USR01', 'jumlah_rencana' => 5, 'tanggal_rencana' => '2026-09-08', 'status' => 'Diproses'],
            ['id_rencana' => 'RPP004', 'id_bom' => 'BOM005', 'id_pengguna' => 'USR01', 'jumlah_rencana' => 8, 'tanggal_rencana' => '2026-09-15', 'status' => 'Diproses'],
            ['id_rencana' => 'RPP005', 'id_bom' => 'BOM006', 'id_pengguna' => 'USR01', 'jumlah_rencana' => 6, 'tanggal_rencana' => '2026-09-22', 'status' => 'Draft'],
            ['id_rencana' => 'RPP006', 'id_bom' => 'BOM002', 'id_pengguna' => 'USR01', 'jumlah_rencana' => 15, 'tanggal_rencana' => '2026-09-25', 'status' => 'Draft'],
        ]);

        DB::table('log_status_produksi')->insert([
            ['id_log' => 'LOG001', 'id_rencana' => 'RPP001', 'id_pengguna' => 'USR01', 'status' => 'Draft', 'waktu_perubahan' => '2026-09-01 08:00:00'],
            ['id_log' => 'LOG002', 'id_rencana' => 'RPP001', 'id_pengguna' => 'USR02', 'status' => 'Diproses', 'waktu_perubahan' => '2026-09-01 10:00:00'],
            ['id_log' => 'LOG003', 'id_rencana' => 'RPP001', 'id_pengguna' => 'USR02', 'status' => 'Selesai', 'waktu_perubahan' => '2026-09-05 16:00:00'],
            ['id_log' => 'LOG004', 'id_rencana' => 'RPP002', 'id_pengguna' => 'USR01', 'status' => 'Draft', 'waktu_perubahan' => '2026-09-03 08:00:00'],
            ['id_log' => 'LOG005', 'id_rencana' => 'RPP002', 'id_pengguna' => 'USR02', 'status' => 'Diproses', 'waktu_perubahan' => '2026-09-03 10:30:00'],
            ['id_log' => 'LOG006', 'id_rencana' => 'RPP002', 'id_pengguna' => 'USR02', 'status' => 'Selesai', 'waktu_perubahan' => '2026-09-09 15:30:00'],
            ['id_log' => 'LOG007', 'id_rencana' => 'RPP003', 'id_pengguna' => 'USR01', 'status' => 'Draft', 'waktu_perubahan' => '2026-09-08 08:00:00'],
            ['id_log' => 'LOG008', 'id_rencana' => 'RPP003', 'id_pengguna' => 'USR02', 'status' => 'Diproses', 'waktu_perubahan' => '2026-09-10 09:00:00'],
            ['id_log' => 'LOG009', 'id_rencana' => 'RPP004', 'id_pengguna' => 'USR01', 'status' => 'Draft', 'waktu_perubahan' => '2026-09-15 08:00:00'],
            ['id_log' => 'LOG010', 'id_rencana' => 'RPP004', 'id_pengguna' => 'USR02', 'status' => 'Diproses', 'waktu_perubahan' => '2026-09-17 09:00:00'],
            ['id_log' => 'LOG011', 'id_rencana' => 'RPP005', 'id_pengguna' => 'USR01', 'status' => 'Draft', 'waktu_perubahan' => '2026-09-22 08:00:00'],
            ['id_log' => 'LOG012', 'id_rencana' => 'RPP006', 'id_pengguna' => 'USR01', 'status' => 'Draft', 'waktu_perubahan' => '2026-09-25 08:00:00'],
        ]);
    }
}
