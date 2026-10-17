<?php

namespace App\Providers;

use App\Models\Pengguna;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Login memakai tabel `pengguna`, apa pun isi .env. Tanpa baris ini, Laravel
        // memakai App\Models\User (tabel `users`) bila AUTH_MODEL belum diatur.
        config(['auth.providers.users.model' => Pengguna::class]);
    }

    public function boot(): void
    {
        //
    }
}
