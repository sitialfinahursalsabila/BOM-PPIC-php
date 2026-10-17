<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $t) {
            $t->string('id_produk', 10)->primary();
            $t->string('kode_produk', 20)->unique();
            $t->string('nama_produk', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
