@extends('layouts.app')
@section('judul', 'Akses ditolak')
@section('isi')
<h1>Akses ditolak</h1>
<div class="alert err">{{ $exception->getMessage() ?: 'Peran Anda tidak memiliki akses ke halaman ini.' }}</div>
<p><a class="btn" href="{{ route('dashboard') }}">Kembali ke Dashboard</a></p>
@endsection
