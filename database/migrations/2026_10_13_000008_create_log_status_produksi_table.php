<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_status_produksi', function (Blueprint $t) {
            $t->string('id_log', 10)->primary();
            $t->string('id_rencana', 10);
            $t->string('id_pengguna', 10);
            $t->string('status', 10);
            $t->dateTime('waktu_perubahan');
            $t->foreign('id_rencana')->references('id_rencana')->on('rencana_produksi')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_status_produksi');
    }
};
