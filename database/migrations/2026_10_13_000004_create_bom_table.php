<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bom', function (Blueprint $t) {
            $t->string('id_bom', 10)->primary();
            $t->string('id_produk', 10);
            $t->integer('versi');
            $t->string('status', 10);
            $t->date('tanggal_dibuat');
            $t->unique(['id_produk', 'versi'], 'uq_bom_produk_versi');
            $t->foreign('id_produk')->references('id_produk')->on('produk')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bom');
    }
};
