<?php

namespace App\Http\Controllers;

use App\Models\RencanaProduksi;
use App\Services\KebutuhanBahan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hitung = RencanaProduksi::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        $terbaru = RencanaProduksi::with('bom.produk')->orderByDesc('tanggal_rencana')->limit(5)->get();

        // Bahan belum tersedia (BR-10) untuk PPIC, Gudang, dan Manajer
        $kurang = [];
        if (auth()->user()->punyaPeran('ppic', 'gudang', 'manajer')) {
            $aktif = RencanaProduksi::with('bom.produk')
                ->whereIn('status', ['Draft', 'Diproses'])->orderBy('tanggal_rencana')->get();
            foreach ($aktif as $rencana) {
                foreach ((new KebutuhanBahan())->hitung($rencana)->filter(fn ($b) => ! $b['cukup']) as $bahan) {
                    $kurang[] = ['rencana' => $rencana, 'bahan' => $bahan];
                }
            }
        }

        return view('dashboard', compact('hitung', 'terbaru', 'kurang'));
    }
}
