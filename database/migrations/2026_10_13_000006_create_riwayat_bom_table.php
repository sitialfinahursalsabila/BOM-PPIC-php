<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_bom', function (Blueprint $t) {
            $t->string('id_riwayat', 10)->primary();
            $t->string('id_bom', 10);
            $t->string('id_pengguna', 10);
            $t->dateTime('tanggal_perubahan');
            $t->string('keterangan', 200);
            $t->foreign('id_bom')->references('id_bom')->on('bom')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_bom');
    }
};
