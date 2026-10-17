@extends('layouts.app')
@section('judul', $item->exists ? 'Ubah Item' : 'Tambah Item')
@section('isi')
<div class="crumb">Master Item &gt; {{ $item->exists ? 'Ubah' : 'Tambah' }}</div>
<h1>{{ $item->exists ? 'Ubah Item' : 'Tambah Item' }}</h1>
<div class="card" style="max-width:520px">
<form method="POST" action="{{ $item->exists ? route('items.update', $item) : route('items.store') }}">
  @csrf
  @if($item->exists) @method('PUT') @endif
  <label for="kode_item">Kode item</label>
  <input id="kode_item" name="kode_item" value="{{ old('kode_item', $item->kode_item) }}">
  <label for="deskripsi">Deskripsi</label>
  <input id="deskripsi" name="deskripsi" value="{{ old('deskripsi', $item->deskripsi) }}">
  <label for="satuan">Satuan</label>
  <input id="satuan" name="satuan" value="{{ old('satuan', $item->satuan) }}" placeholder="pcs, lembar, meter">
  <label for="harga_satuan">Harga satuan (Rp)</label>
  <input id="harga_satuan" type="number" step="0.01" name="harga_satuan" value="{{ old('harga_satuan', $item->harga_satuan) }}">
  <label for="stok">Stok</label>
  <input id="stok" type="number" step="0.01" name="stok" value="{{ old('stok', $item->stok) }}">
  <div class="aksi"><button class="btn">Simpan</button><a class="btn sec" href="{{ route('items.index') }}">Batal</a></div>
</form>
</div>
@endsection
