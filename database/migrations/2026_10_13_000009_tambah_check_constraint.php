<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * CHECK constraint dari Modul 5. Dijalankan hanya pada MySQL/MariaDB/TiDB.
 * Bila versi TiDB belum mendukung CHECK, pernyataan dilewati. Aplikasi tetap
 * memvalidasi nilai yang sama di sisi server, jadi aturan tidak bergantung pada CHECK.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        $aturan = [
            "ALTER TABLE pengguna ADD CONSTRAINT chk_pengguna_peran CHECK (peran IN ('Staff PPIC','Staff Produksi','Staff Gudang','Manajer Produksi','Direktur'))",
            'ALTER TABLE item ADD CONSTRAINT chk_item_harga CHECK (harga_satuan >= 0)',
            'ALTER TABLE item ADD CONSTRAINT chk_item_stok CHECK (stok >= 0)',
            "ALTER TABLE bom ADD CONSTRAINT chk_bom_status CHECK (status IN ('Aktif','Nonaktif'))",
            "ALTER TABLE detail_bom ADD CONSTRAINT chk_detail_level CHECK (level IN ('Bahan Utama','Sub Assembly','Komponen Kecil'))",
            'ALTER TABLE detail_bom ADD CONSTRAINT chk_detail_jumlah CHECK (jumlah > 0)',
            "ALTER TABLE rencana_produksi ADD CONSTRAINT chk_rencana_status CHECK (status IN ('Draft','Diproses','Selesai'))",
            'ALTER TABLE rencana_produksi ADD CONSTRAINT chk_rencana_jumlah CHECK (jumlah_rencana > 0)',
            "ALTER TABLE log_status_produksi ADD CONSTRAINT chk_log_status CHECK (status IN ('Draft','Diproses','Selesai'))",
        ];

        foreach ($aturan as $sql) {
            try {
                DB::statement($sql);
            } catch (\Throwable $e) {
                // CHECK tidak didukung pada versi ini, dilewati.
            }
        }
    }

    public function down(): void
    {
        // Constraint ikut terhapus saat tabel di-drop oleh migrasi sebelumnya.
    }
};
