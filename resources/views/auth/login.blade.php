@extends('layouts.app')
@section('judul', 'Login')
@section('isi')
<div class="tengah card">
  <h1 style="text-align:center">Masuk ke SI BOM &amp; PPIC</h1>
  <p class="sub" style="text-align:center">Gunakan akun sesuai peran Anda</p>
  @if(session('sukses'))<div class="alert ok">{{ session('sukses') }}</div>@endif
  @if($errors->any())<div class="alert err">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>@endif
  <form method="POST" action="{{ route('login.proses') }}">
    @csrf
    <label for="username">Username</label>
    <input id="username" name="username" value="{{ old('username') }}" placeholder="contoh: budi.produksi" autofocus>
    <label for="password">Kata sandi</label>
    <input id="password" type="password" name="password">
    <div class="aksi"><button class="btn" style="width:100%">Masuk</button></div>
  </form>
</div>
@endsection
