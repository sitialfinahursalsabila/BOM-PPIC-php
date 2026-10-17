<?php

namespace App\Http\Controllers;

use App\Models\DetailBom;
use App\Models\Item;
use App\Services\IdGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/** CRUD master item (UC-01). Hanya Staff PPIC (BR-05). */
class ItemController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $items = Item::query()
            ->when($q !== '', fn ($query) => $query->where(
                fn ($w) => $w->where('kode_item', 'like', "%{$q}%")->orWhere('deskripsi', 'like', "%{$q}%")
            ))
            ->orderBy('kode_item')->get();

        return view('items.index', compact('items', 'q'));
    }

    public function create()
    {
        return view('items.form', ['item' => new Item()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->aturan(null), $this->pesan(), $this->nama());
        $data['id_item'] = IdGenerator::berikutnya('item', 'id_item', 'ITM');
        Item::create($data);

        return redirect()->route('items.index')->with('sukses', "Item {$data['kode_item']} berhasil disimpan.");
    }

    public function edit(Item $item)
    {
        return view('items.form', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate($this->aturan($item), $this->pesan(), $this->nama());
        $item->update($data);

        return redirect()->route('items.index')->with('sukses', "Item {$item->kode_item} berhasil diperbarui.");
    }

    public function destroy(Item $item)
    {
        // Perlindungan relasi: item yang dipakai BOM tidak boleh dihapus (ON DELETE RESTRICT)
        if (DetailBom::where('id_item', $item->id_item)->exists()) {
            return back()->with('galat', "Item {$item->kode_item} tidak dapat dihapus karena masih dipakai pada BOM.");
        }

        try {
            $item->delete();
        } catch (QueryException $e) {
            return back()->with('galat', "Item {$item->kode_item} tidak dapat dihapus karena masih berelasi dengan data lain.");
        }

        return redirect()->route('items.index')->with('sukses', "Item {$item->kode_item} berhasil dihapus.");
    }

    private function aturan(?Item $item): array
    {
        return [
            'kode_item' => ['required', 'string', 'max:20', Rule::unique('item', 'kode_item')->ignore($item?->id_item, 'id_item')],
            'deskripsi' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_satuan' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stok' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    private function pesan(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'max' => ':attribute melebihi batas yang diizinkan.',
            'unique' => ':attribute sudah dipakai item lain.',
        ];
    }

    private function nama(): array
    {
        return [
            'kode_item' => 'Kode item', 'deskripsi' => 'Deskripsi', 'satuan' => 'Satuan',
            'harga_satuan' => 'Harga satuan', 'stok' => 'Stok',
        ];
    }
}
