<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatBom extends Model
{
    protected $table = 'riwayat_bom';
    protected $primaryKey = 'id_riwayat';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['tanggal_perubahan' => 'datetime'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
