@extends('layouts.app')
@section('judul', 'Master Item')
@section('isi')
<div class="crumb">Master Item</div>
<h1>Master Item</h1>
<form class="baris" method="GET" action="{{ route('items.index') }}">
  <input name="q" value="{{ $q }}" placeholder="Cari kode / deskripsi">
  <button class="btn sec">Cari</button>
  <a class="btn" href="{{ route('items.create') }}">+ Tambah Item</a>
</form>
<table>
  <tr><th>Kode</th><th>Deskripsi</th><th>Satuan</th><th>Harga</th><th>Stok</th><th>Aksi</th></tr>
  @forelse($items as $i)
    <tr>
      <td>{{ $i->kode_item }}</td><td>{{ $i->deskripsi }}</td><td>{{ $i->satuan }}</td>
      <td>{{ \App\Support\Angka::rupiah($i->harga_satuan) }}</td><td>{{ \App\Support\Angka::fmt($i->stok) }}</td>
      <td>
        <a href="{{ route('items.edit', $i) }}">Ubah</a> ·
        <form method="POST" action="{{ route('items.destroy', $i) }}" style="display:inline"
              onsubmit="return confirm('Hapus item {{ $i->kode_item }}?')">@csrf @method('DELETE')
          <button class="linkbtn" style="display:inline;width:auto;padding:0;color:#B91C1C">Hapus</button></form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6" class="muted">Tidak ada item.</td></tr>
  @endforelse
</table>
@endsection
