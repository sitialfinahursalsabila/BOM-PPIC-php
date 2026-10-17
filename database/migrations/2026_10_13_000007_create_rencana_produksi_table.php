<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rencana_produksi', function (Blueprint $t) {
            $t->string('id_rencana', 10)->primary();
            $t->string('id_bom', 10);
            $t->string('id_pengguna', 10);
            $t->integer('jumlah_rencana');
            $t->date('tanggal_rencana');
            $t->string('status', 10);
            $t->foreign('id_bom')->references('id_bom')->on('bom')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rencana_produksi');
    }
};
