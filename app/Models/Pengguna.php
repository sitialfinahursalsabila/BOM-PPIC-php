<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Tabel pengguna (Modul 5). Kata sandi disimpan di kolom password_hash.
 */
class Pengguna extends Authenticatable
{
    public const PERAN = [
        'ppic' => 'Staff PPIC',
        'produksi' => 'Staff Produksi',
        'gudang' => 'Staff Gudang',
        'manajer' => 'Manajer Produksi',
        'direktur' => 'Direktur',
    ];

    protected $table = 'pengguna';
    protected $primaryKey = 'id_pengguna';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['password_hash'];

    // Tabel pengguna tidak memiliki kolom remember_token.
    protected $rememberTokenName = '';

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /** Authorization: apakah pengguna memiliki salah satu peran (kunci: ppic, produksi, ...). */
    public function punyaPeran(string ...$kunci): bool
    {
        foreach ($kunci as $k) {
            if ((self::PERAN[$k] ?? null) === $this->peran) {
                return true;
            }
        }
        return false;
    }
}
