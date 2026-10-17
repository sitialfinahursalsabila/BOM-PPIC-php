@extends('layouts.app')
@section('judul', 'Struktur BOM')
@section('isi')
<div class="crumb">BOM &gt; Struktur BOM</div>
<h1>Struktur BOM {{ $bom->produk->nama_produk }}</h1>
<p class="sub">{{ $bom->id_bom }} · Versi {{ $bom->versi }} <span class="badge b-{{ strtolower($bom->status) }}">{{ $bom->status }}</span></p>

@if($masalah)
  <div class="alert err"><strong>BOM belum lengkap</strong><ul>@foreach($masalah as $m)<li>{{ $m }}</li>@endforeach</ul></div>
@else
  <div class="alert ok">BOM lengkap dan memenuhi syarat validasi (UC-11).</div>
@endif

<table>
  <tr><th>Level</th><th>Kode</th><th>Deskripsi</th><th>Jumlah</th><th>Satuan</th></tr>
  @foreach($urut as $u)
    @php $b = $u['baris']; @endphp
    <tr>
      <td>{{ $b->level }}</td><td>{{ $b->item->kode_item }}</td>
      <td style="padding-left:{{ 12 + $u['kedalaman'] * 22 }}px">{{ $u['kedalaman'] ? '↳ ' : '' }}{{ $b->item->deskripsi }}</td>
      <td>{{ \App\Support\Angka::fmt($b->jumlah) }}</td><td>{{ $b->item->satuan }}</td>
    </tr>
  @endforeach
</table>

<h2>Riwayat perubahan</h2>
<table>
  <tr><th>Waktu</th><th>Pengguna</th><th>Keterangan</th></tr>
  @foreach($riwayat as $r)
    <tr><td>{{ $r->tanggal_perubahan->format('d/m/Y H:i') }}</td><td>{{ $r->pengguna->nama }}</td><td>{{ $r->keterangan }}</td></tr>
  @endforeach
</table>
<p><a href="{{ route('bom.index') }}">← Kembali</a></p>
@endsection
