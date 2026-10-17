<?php

namespace App\Services;

use App\Models\DetailBom;
use App\Models\Item;
use App\Models\RencanaProduksi;
use Illuminate\Support\Collection;

/**
 * Menghitung kebutuhan bahan (BR-06, BR-10):
 * jumlah per unit dikali jumlah rencana, mencakup seluruh level struktur BOM.
 */
class KebutuhanBahan
{
    /** @return Collection<int,array{item:Item,kebutuhan:float,stok:float,kurang:float,cukup:bool}> */
    public function hitung(RencanaProduksi $rencana): Collection
    {
        $baris = DetailBom::where('id_bom', $rencana->id_bom)->get();
        $anak = $baris->groupBy(fn ($b) => $b->id_induk ?? '');
        $total = [];

        $telusur = function (string $idInduk, float $pengali, int $kedalaman) use (&$telusur, $anak, &$total) {
            if ($kedalaman > 10) {
                return; // pengaman bila data induk saling merujuk
            }
            foreach ($anak->get($idInduk, collect()) as $b) {
                $efektif = $pengali * (float) $b->jumlah;
                $total[$b->id_item] = ($total[$b->id_item] ?? 0) + $efektif;
                $telusur($b->id_detail_bom, $efektif, $kedalaman + 1);
            }
        };
        $telusur('', (float) $rencana->jumlah_rencana, 0);

        $item = Item::whereIn('id_item', array_keys($total))->get()->keyBy('id_item');

        return collect($total)
            ->map(function ($kebutuhan, $idItem) use ($item) {
                $stok = (float) $item[$idItem]->stok;
                $kurang = max(0.0, $kebutuhan - $stok);
                return [
                    'item' => $item[$idItem],
                    'kebutuhan' => (float) $kebutuhan,
                    'stok' => $stok,
                    'kurang' => $kurang,
                    'cukup' => $kurang <= 0,
                ];
            })
            ->sortBy(fn ($r) => $r['item']->kode_item)
            ->values();
    }
}
