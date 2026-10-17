<?php

namespace App\Http\Controllers;

use App\Exceptions\StatusProduksiException;
use App\Models\RencanaProduksi;
use App\Services\KebutuhanBahan;
use App\Services\KelengkapanBom;
use App\Services\StatusProduksiService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** Core module UC-07 Mengubah Status Produksi. */
class StatusProduksiController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        if (! in_array($status, RencanaProduksi::STATUS, true)) {
            $status = null;
        }

        $rencana = RencanaProduksi::with('bom.produk')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderByDesc('tanggal_rencana')->get();

        $bolehUbah = auth()->user()->punyaPeran('produksi');

        return view('status.index', compact('rencana', 'status', 'bolehUbah'));
    }

    public function show(RencanaProduksi $rencana)
    {
        $rencana->load('bom.produk', 'pengguna', 'log.pengguna');
        $kebutuhan = (new KebutuhanBahan())->hitung($rencana);
        $masalah = (new KelengkapanBom())->periksa($rencana->bom);

        return view('status.show', compact('rencana', 'kebutuhan', 'masalah'));
    }

    public function ubah(Request $request, RencanaProduksi $rencana, StatusProduksiService $layanan)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['Diproses', 'Selesai'])],
        ], [
            'status.required' => 'Status baru wajib dipilih.',
            'status.in' => 'Status baru tidak valid.',
        ]);

        try {
            $layanan->ubah($rencana->id_rencana, $data['status'], $request->user());
        } catch (StatusProduksiException $e) {
            return back()->with('galat_judul', $e->getMessage())->with('galat_rincian', $e->rincian);
        }

        return back()->with('sukses', "Status {$rencana->id_rencana} berhasil diubah menjadi {$data['status']}.");
    }
}
