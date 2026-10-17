@extends('layouts.app')
@section('judul', 'BOM')
@section('isi')
<div class="crumb">BOM</div>
<h1>BOM Aktif</h1>
<p class="sub">Satu versi aktif untuk setiap produk (BR-03)</p>
<table>
  <tr><th>Produk</th><th>BOM</th><th>Versi</th><th>Dibuat</th><th>Komponen</th><th></th></tr>
  @foreach($boms as $b)
    <tr>
      <td>{{ $b->produk->nama_produk }} ({{ $b->produk->kode_produk }})</td>
      <td>{{ $b->id_bom }}</td><td>{{ $b->versi }}</td>
      <td>{{ $b->tanggal_dibuat->format('d/m/Y') }}</td><td>{{ $b->detail_count }}</td>
      <td><a href="{{ route('bom.show', $b) }}">Lihat struktur</a></td>
    </tr>
  @endforeach
</table>
@endsection
