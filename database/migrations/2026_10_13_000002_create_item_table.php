<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item', function (Blueprint $t) {
            $t->string('id_item', 10)->primary();
            $t->string('kode_item', 20)->unique();
            $t->string('deskripsi', 150);
            $t->string('satuan', 20);
            $t->decimal('harga_satuan', 12, 2);
            $t->decimal('stok', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
