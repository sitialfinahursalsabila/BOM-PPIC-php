<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_bom', function (Blueprint $t) {
            $t->string('id_detail_bom', 10)->primary();
            $t->string('id_bom', 10);
            $t->string('id_item', 10);
            $t->string('id_induk', 10)->nullable();
            $t->decimal('jumlah', 10, 2);
            $t->string('level', 20);
            $t->foreign('id_bom')->references('id_bom')->on('bom')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreign('id_item')->references('id_item')->on('item')->cascadeOnUpdate()->restrictOnDelete();
            $t->foreign('id_induk')->references('id_detail_bom')->on('detail_bom')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_bom');
    }
};
