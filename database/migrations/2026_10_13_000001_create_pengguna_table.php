<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $t) {
            $t->string('id_pengguna', 10)->primary();
            $t->string('nama', 100);
            $t->string('username', 50)->unique();
            $t->string('password_hash', 255);
            $t->string('peran', 30);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
