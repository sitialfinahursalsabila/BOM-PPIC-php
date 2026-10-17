<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBom extends Model
{
    protected $table = 'detail_bom';
    protected $primaryKey = 'id_detail_bom';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];

    public function bom()
    {
        return $this->belongsTo(Bom::class, 'id_bom', 'id_bom');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_item', 'id_item');
    }

    public function induk()
    {
        return $this->belongsTo(DetailBom::class, 'id_induk', 'id_detail_bom');
    }

    public function anak()
    {
        return $this->hasMany(DetailBom::class, 'id_induk', 'id_detail_bom');
    }
}
