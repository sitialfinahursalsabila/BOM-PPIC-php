@extends('layouts.app')
@section('judul', 'Dashboard')
@section('isi')
<h1>Dashboard</h1>
<p class="sub">Ringkasan status rencana produksi</p>
<div class="cards">
  @foreach(['Draft','Diproses','Selesai'] as $s)
    <div class="card">Rencana {{ $s }}<b>{{ $hitung[$s] ?? 0 }}</b></div>
  @endforeach
</div>

<h2>Rencana produksi terbaru</h2>
<table>
  <tr><th>ID</th><th>Produk</th><th>Jumlah</th><th>Tanggal</th><th>Status</th></tr>
  @foreach($terbaru as $r)
    <tr>
      <td><a href="{{ route('status.show', $r) }}">{{ $r->id_rencana }}</a></td>
      <td>{{ $r->bom->produk->nama_produk }}</td>
      <td>{{ $r->jumlah_rencana }}</td>
      <td>{{ $r->tanggal_rencana->format('d/m/Y') }}</td>
      <td><span class="badge b-{{ strtolower($r->status) }}">{{ $r->status }}</span></td>
    </tr>
  @endforeach
</table>
<p><a class="btn" href="{{ route('status.index') }}">Status Produksi</a></p>

@if(auth()->user()->punyaPeran('ppic','gudang','manajer'))
  <h2>Bahan belum tersedia</h2>
  @forelse($kurang as $k)
    <div class="alert err">{{ $k['rencana']->id_rencana }} ({{ $k['rencana']->bom->produk->nama_produk }}):
      {{ $k['bahan']['item']->kode_item }} kurang {{ \App\Support\Angka::fmt($k['bahan']['kurang']) }} {{ $k['bahan']['item']->satuan }}</div>
  @empty
    <p class="muted">Semua bahan untuk rencana Draft dan Diproses mencukupi.</p>
  @endforelse
@endif
@endsection
