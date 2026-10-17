@extends('layouts.app')
@section('judul', 'Detail Rencana Produksi')
@section('isi')
<div class="crumb">Status Produksi &gt; {{ $rencana->id_rencana }}</div>
<h1>{{ $rencana->id_rencana }} · {{ $rencana->bom->produk->nama_produk }}</h1>
<p class="sub">{{ $rencana->jumlah_rencana }} unit · {{ $rencana->tanggal_rencana->format('d/m/Y') }} · BOM {{ $rencana->id_bom }}
  <span class="badge b-{{ strtolower($rencana->status) }}">{{ $rencana->status }}</span></p>

@if($masalah)
  <div class="alert err"><strong>BOM belum lengkap</strong><ul>@foreach($masalah as $m)<li>{{ $m }}</li>@endforeach</ul></div>
@endif

<h2>Kebutuhan bahan</h2>
<table>
  <tr><th>Kode</th><th>Deskripsi</th><th>Kebutuhan</th><th>Stok</th><th>Keterangan</th></tr>
  @foreach($kebutuhan as $k)
    <tr>
      <td>{{ $k['item']->kode_item }}</td><td>{{ $k['item']->deskripsi }}</td>
      <td>{{ \App\Support\Angka::fmt($k['kebutuhan']) }}</td><td>{{ \App\Support\Angka::fmt($k['stok']) }}</td>
      <td>{{ $k['cukup'] ? 'Cukup' : 'Belum tersedia, kurang ' . \App\Support\Angka::fmt($k['kurang']) . ' ' . $k['item']->satuan }}</td>
    </tr>
  @endforeach
</table>

<h2>Riwayat status</h2>
<table>
  <tr><th>Waktu</th><th>Status</th><th>Pengguna</th></tr>
  @foreach($rencana->log as $l)
    <tr><td>{{ $l->waktu_perubahan->format('d/m/Y H:i') }}</td>
      <td><span class="badge b-{{ strtolower($l->status) }}">{{ $l->status }}</span></td><td>{{ $l->pengguna->nama }}</td></tr>
  @endforeach
</table>
<p><a href="{{ route('status.index') }}">← Kembali</a></p>
@endsection
