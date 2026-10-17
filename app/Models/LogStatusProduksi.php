<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStatusProduksi extends Model
{
    protected $table = 'log_status_produksi';
    protected $primaryKey = 'id_log';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['waktu_perubahan' => 'datetime'];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }
}
