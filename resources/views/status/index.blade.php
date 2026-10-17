@extends('layouts.app')
@section('judul', 'Status Produksi')
@section('isi')
<div class="crumb">Rencana Produksi &gt; Status Produksi</div>
<h1>Status Produksi</h1>
<form class="baris" method="GET" action="{{ route('status.index') }}">
  <select name="status" style="width:200px" onchange="this.form.submit()">
    <option value="">Semua status</option>
    @foreach(['Draft','Diproses','Selesai'] as $s)<option value="{{ $s }}" @selected($status === $s)>{{ $s }}</option>@endforeach
  </select>
</form>

<table>
  <tr><th>ID</th><th>Produk</th><th>BOM</th><th>Jumlah</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
  @foreach($rencana as $r)
    @php $berikut = \App\Services\StatusProduksiService::statusBerikutnya($r->status); @endphp
    <tr>
      <td>{{ $r->id_rencana }}</td><td>{{ $r->bom->produk->nama_produk }}</td><td>{{ $r->id_bom }}</td>
      <td>{{ $r->jumlah_rencana }}</td><td>{{ $r->tanggal_rencana->format('d/m/Y') }}</td>
      <td><span class="badge b-{{ strtolower($r->status) }}">{{ $r->status }}</span></td>
      <td>
        @if($bolehUbah && $berikut)
          <details class="menu">
            <summary class="btn sec">Action ▾</summary>
            <div class="menu-isi">
              <button type="button" data-ubah
                data-url="{{ route('status.ubah', $r) }}" data-status="{{ $berikut }}"
                data-teks="Ubah status {{ $r->id_rencana }} - {{ $r->bom->produk->nama_produk }} ({{ $r->jumlah_rencana }} unit) dari {{ $r->status }} menjadi {{ $berikut }}?"
                data-cek="{{ $berikut === 'Selesai' ? '1' : '0' }}">{{ $berikut === 'Selesai' ? 'Tandai Selesai' : 'Mulai Produksi' }}</button>
              <a href="{{ route('status.show', $r) }}">Lihat riwayat status</a>
            </div>
          </details>
        @else
          <a href="{{ route('status.show', $r) }}">Detail</a>
        @endif
      </td>
    </tr>
  @endforeach
</table>

<dialog id="dlg">
  <form method="POST" id="dlgForm">
    @csrf
    <h2 style="margin-top:0">Konfirmasi Perubahan Status</h2>
    <p id="dlgTeks"></p>
    <p class="muted" id="dlgCek">Sistem akan memeriksa kelengkapan BOM sebelum menyimpan.</p>
    <input type="hidden" name="status" id="dlgStatus">
    <div class="aksi"><button class="btn" id="dlgYa">Ya</button><button type="button" class="btn sec" id="dlgBatal">Batal</button></div>
  </form>
</dialog>
<script>
const dlg = document.getElementById('dlg');
document.querySelectorAll('[data-ubah]').forEach(function (tombol) {
  tombol.addEventListener('click', function () {
    document.getElementById('dlgForm').action = tombol.dataset.url;
    document.getElementById('dlgStatus').value = tombol.dataset.status;
    document.getElementById('dlgTeks').textContent = tombol.dataset.teks;
    document.getElementById('dlgCek').style.display = tombol.dataset.cek === '1' ? 'block' : 'none';
    document.getElementById('dlgYa').textContent = tombol.dataset.status === 'Selesai' ? 'Ya, Tandai Selesai' : 'Ya, Mulai Produksi';
    dlg.showModal();
  });
});
document.getElementById('dlgBatal').addEventListener('click', function () { dlg.close(); });
</script>
@endsection
