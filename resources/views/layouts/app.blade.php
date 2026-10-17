<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('judul', 'Sistem') - SI BOM &amp; PPIC</title>
<style>
:root{--navy:#0F3D66;--pri:#1E5AA8;--bg:#F3F6FA;--line:#D3DCE8;--txt:#1F2937;--mute:#6B7280}
*{box-sizing:border-box}
body{margin:0;font-family:Calibri,Carlito,Arial,sans-serif;background:var(--bg);color:var(--txt);font-size:16px}
.head{background:var(--navy);color:#fff;padding:14px 24px;display:flex;justify-content:space-between;align-items:center}
.head strong{font-size:20px}
.wrap{display:flex;min-height:calc(100vh - 52px)}
.side{width:200px;background:#fff;border-right:1px solid var(--line);padding:14px 10px;display:flex;flex-direction:column;gap:6px}
.side a,.linkbtn{display:block;padding:10px 14px;border-radius:8px;color:var(--txt);text-decoration:none;background:none;border:0;font:inherit;text-align:left;cursor:pointer;width:100%}
.side a.aktif{background:#DCE9F7;color:var(--navy);font-weight:700}
.side a:hover,.linkbtn:hover{background:#EEF3FA}
.isi{flex:1;padding:24px 32px;max-width:1100px}
h1{margin:0 0 4px;font-size:28px}
.sub{color:var(--mute);margin:0 0 18px}
.crumb{color:var(--mute);font-size:14px;margin-bottom:2px}
.card{background:#fff;border:1px solid var(--line);border-radius:12px;padding:16px 20px}
.cards{display:flex;gap:16px;margin-bottom:22px;flex-wrap:wrap}
.cards .card{min-width:200px}
.cards b{font-size:36px;display:block}
table{border-collapse:collapse;width:100%;background:#fff}
th{background:#E8EEF6;text-align:left}
th,td{border:1px solid var(--line);padding:9px 12px}
.btn{display:inline-block;background:var(--pri);color:#fff;border:2px solid var(--pri);border-radius:8px;padding:8px 16px;font:inherit;font-weight:700;cursor:pointer;text-decoration:none}
.btn.sec{background:#fff;color:var(--pri)}
.badge{display:inline-block;border-radius:14px;padding:2px 12px;font-size:14px;font-weight:700}
.b-draft{background:#E5E7EB;color:#4B5563}.b-diproses{background:#FEF3C7;color:#92400E}
.b-selesai,.b-aktif{background:#DCFCE7;color:#166534}.b-nonaktif{background:#E5E7EB;color:#4B5563}
.alert{border-radius:10px;padding:12px 16px;margin-bottom:16px}
.ok{background:#DCFCE7;border:1px solid #15803D;color:#166534}
.err{background:#FEE2E2;border:1px solid #B91C1C;color:#7F1D1D}
.err ul{margin:6px 0 0 18px;padding:0}
label{display:block;font-weight:700;margin:12px 0 4px}
input,select{width:100%;padding:9px 12px;border:2px solid var(--line);border-radius:6px;font:inherit}
.baris{display:flex;gap:10px;align-items:center;margin-bottom:14px;flex-wrap:wrap}
.baris input{width:260px}
.muted{color:var(--mute)}
details.menu{position:relative;display:inline-block}
details.menu summary{list-style:none}
details.menu summary::-webkit-details-marker{display:none}
.menu-isi{position:absolute;right:0;z-index:5;background:#fff;border:1px solid var(--line);border-radius:8px;min-width:170px;box-shadow:0 4px 12px #0002}
.menu-isi button,.menu-isi a{display:block;width:100%;padding:9px 14px;background:none;border:0;font:inherit;text-align:left;color:var(--txt);text-decoration:none;cursor:pointer}
.menu-isi button:hover,.menu-isi a:hover{background:#EEF3FA}
dialog{border:1px solid var(--line);border-radius:14px;padding:24px 28px;max-width:520px}
dialog::backdrop{background:#0F172A88}
.aksi{display:flex;gap:10px;margin-top:18px}
.tengah{max-width:420px;margin:70px auto}
</style>
</head>
<body>
@auth
<div class="head"><strong>SI BOM &amp; PPIC</strong><span>{{ auth()->user()->nama }} · {{ auth()->user()->peran }}</span></div>
<div class="wrap">
  <nav class="side">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'aktif' : '' }}">Dashboard</a>
    @if(auth()->user()->punyaPeran('ppic'))
      <a href="{{ route('items.index') }}" class="{{ request()->routeIs('items.*') ? 'aktif' : '' }}">Master Item</a>
    @endif
    <a href="{{ route('bom.index') }}" class="{{ request()->routeIs('bom.*') ? 'aktif' : '' }}">BOM</a>
    <a href="{{ route('status.index') }}" class="{{ request()->routeIs('status.*') ? 'aktif' : '' }}">Status Produksi</a>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="linkbtn">Keluar</button></form>
  </nav>
  <main class="isi">
    @if(session('sukses'))<div class="alert ok">{{ session('sukses') }}</div>@endif
    @if(session('galat'))<div class="alert err">{{ session('galat') }}</div>@endif
    @if(session('galat_judul'))
      <div class="alert err"><strong>{{ session('galat_judul') }}</strong>
        @if(session('galat_rincian'))<ul>@foreach(session('galat_rincian') as $r)<li>{{ $r }}</li>@endforeach</ul>@endif
      </div>
    @endif
    @if($errors->any())
      <div class="alert err"><strong>Data belum dapat disimpan.</strong><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    @yield('isi')
  </main>
</div>
@else
  @yield('isi')
@endauth
</body>
</html>
