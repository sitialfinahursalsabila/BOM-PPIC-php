<?php

namespace App\Services;

use App\Models\Bom;
use App\Models\DetailBom;

/**
 * UC-11 Memvalidasi Kelengkapan BOM (include dari UC-07, BR-09, FR-07).
 *
 * Definisi "BOM lengkap" yang dipakai aplikasi (asumsi, perlu dikonfirmasi):
 *  1. BOM memiliki minimal satu komponen (BR-02).
 *  2. Jumlah setiap komponen lebih dari nol (BR-02).
 *  3. Setiap komponen merujuk item yang terdaftar (BR-01).
 *  4. Setiap induk menunjuk baris pada BOM yang sama.
 *  5. Setiap Sub Assembly memiliki minimal satu komponen di bawahnya.
 *
 * @return array<int,string> daftar masalah, kosong bila BOM lengkap
 */
class KelengkapanBom
{
    public function periksa(Bom $bom): array
    {
        $baris = DetailBom::with('item')->where('id_bom', $bom->id_bom)->get();

        if ($baris->isEmpty()) {
            return ['BOM belum memiliki komponen.'];
        }

        $masalah = [];
        $semuaId = $baris->pluck('id_detail_bom')->all();

        foreach ($baris as $b) {
            $nama = $b->item?->kode_item ?? $b->id_item;

            if ($b->item === null) {
                $masalah[] = "Komponen {$b->id_detail_bom} merujuk item yang tidak terdaftar.";
            }
            if ((float) $b->jumlah <= 0) {
                $masalah[] = "Jumlah komponen {$nama} harus lebih dari nol.";
            }
            if ($b->id_induk !== null && ! in_array($b->id_induk, $semuaId, true)) {
                $masalah[] = "Induk komponen {$nama} tidak ditemukan pada BOM ini.";
            }
            if ($b->level === 'Sub Assembly' && ! $baris->contains(fn ($x) => $x->id_induk === $b->id_detail_bom)) {
                $masalah[] = "Sub assembly {$nama} belum memiliki komponen di bawahnya.";
            }
        }

        return $masalah;
    }
}
