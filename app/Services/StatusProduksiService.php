<?php

namespace App\Services;

use App\Exceptions\StatusProduksiException;
use App\Models\LogStatusProduksi;
use App\Models\Pengguna;
use App\Models\RencanaProduksi;
use Illuminate\Support\Facades\DB;

/**
 * Core module: UC-07 Mengubah Status Produksi.
 * Alur: Input -> Validation (BR-08, BR-09 via UC-11) -> Processing -> Database -> Result.
 * Aturan ini dijaga di aplikasi karena database tidak dapat menjaganya (Modul 5, pengujian 7).
 */
class StatusProduksiService
{
    /** Urutan sah (BR-08). */
    public const URUTAN = ['Draft' => 'Diproses', 'Diproses' => 'Selesai'];

    public static function statusBerikutnya(string $status): ?string
    {
        return self::URUTAN[$status] ?? null;
    }

    public function ubah(string $idRencana, string $statusBaru, Pengguna $pengguna): RencanaProduksi
    {
        return DB::transaction(function () use ($idRencana, $statusBaru, $pengguna) {
            $rencana = RencanaProduksi::with('bom.produk')->lockForUpdate()->find($idRencana);

            if (! $rencana) {
                throw new StatusProduksiException('Rencana produksi tidak ditemukan.');
            }

            if (! in_array($statusBaru, ['Diproses', 'Selesai'], true)) {
                throw new StatusProduksiException("Status {$statusBaru} tidak dikenal.");
            }

            // BR-08: status berurutan Draft -> Diproses -> Selesai
            if (self::statusBerikutnya($rencana->status) !== $statusBaru) {
                throw new StatusProduksiException(
                    'Status harus berurutan: Draft, Diproses, lalu Selesai.',
                    ["Status saat ini {$rencana->status} tidak dapat langsung diubah menjadi {$statusBaru}. (BR-08)"]
                );
            }

            // BR-09 + UC-11: Selesai hanya bila BOM lengkap
            if ($statusBaru === 'Selesai') {
                $masalah = (new KelengkapanBom())->periksa($rencana->bom);
                if ($masalah) {
                    throw new StatusProduksiException(
                        'Status Selesai tidak dapat disimpan.',
                        array_merge(
                            ["Data BOM {$rencana->id_bom} ({$rencana->bom->produk->nama_produk}) belum lengkap. (BR-09)"],
                            $masalah
                        )
                    );
                }
            }

            $rencana->status = $statusBaru;
            $rencana->save();

            LogStatusProduksi::create([
                'id_log' => IdGenerator::berikutnya('log_status_produksi', 'id_log', 'LOG'),
                'id_rencana' => $rencana->id_rencana,
                'id_pengguna' => $pengguna->id_pengguna,
                'status' => $statusBaru,
                'waktu_perubahan' => now(),
            ]);

            return $rencana;
        });
    }
}
