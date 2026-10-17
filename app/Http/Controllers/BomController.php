<?php

namespace App\Http\Controllers;

use App\Models\Bom;
use App\Models\DetailBom;
use App\Models\RiwayatBom;
use App\Services\KelengkapanBom;

/** Tampilan BOM aktif dan struktur berjenjang (baca saja pada Modul 7). */
class BomController extends Controller
{
    public function index()
    {
        $boms = Bom::with('produk')->withCount('detail')->where('status', 'Aktif')->orderBy('id_produk')->get();

        return view('bom.index', compact('boms'));
    }

    public function show(Bom $bom)
    {
        $bom->load('produk');
        $baris = DetailBom::with('item')->where('id_bom', $bom->id_bom)->orderBy('id_detail_bom')->get();
        $anak = $baris->groupBy(fn ($b) => $b->id_induk ?? '');

        $urut = [];
        $susun = function (string $induk, int $kedalaman) use (&$susun, $anak, &$urut) {
            if ($kedalaman > 10) {
                return;
            }
            foreach ($anak->get($induk, collect()) as $b) {
                $urut[] = ['baris' => $b, 'kedalaman' => $kedalaman];
                $susun($b->id_detail_bom, $kedalaman + 1);
            }
        };
        $susun('', 0);

        $masalah = (new KelengkapanBom())->periksa($bom);
        $riwayat = RiwayatBom::with('pengguna')->where('id_bom', $bom->id_bom)->orderByDesc('tanggal_perubahan')->get();

        return view('bom.show', compact('bom', 'urut', 'masalah', 'riwayat'));
    }
}
