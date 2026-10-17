<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bom extends Model
{
    protected $table = 'bom';
    protected $primaryKey = 'id_bom';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['tanggal_dibuat' => 'date'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    public function detail()
    {
        return $this->hasMany(DetailBom::class, 'id_bom', 'id_bom');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatBom::class, 'id_bom', 'id_bom');
    }

    public function rencana()
    {
        return $this->hasMany(RencanaProduksi::class, 'id_bom', 'id_bom');
    }
}
