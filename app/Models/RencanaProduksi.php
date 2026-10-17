<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RencanaProduksi extends Model
{
    public const STATUS = ['Draft', 'Diproses', 'Selesai'];

    protected $table = 'rencana_produksi';
    protected $primaryKey = 'id_rencana';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = ['tanggal_rencana' => 'date'];

    public function bom()
    {
        return $this->belongsTo(Bom::class, 'id_bom', 'id_bom');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function log()
    {
        return $this->hasMany(LogStatusProduksi::class, 'id_rencana', 'id_rencana')->orderBy('waktu_perubahan');
    }
}
