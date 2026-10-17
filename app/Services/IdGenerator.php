<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/** Membuat id berformat Modul 5, misalnya ITM011 atau LOG013. */
class IdGenerator
{
    public static function berikutnya(string $tabel, string $kolom, string $prefix, int $lebar = 3): string
    {
        $terakhir = DB::table($tabel)
            ->where($kolom, 'like', $prefix . '%')
            ->orderByDesc($kolom)
            ->value($kolom);

        $nomor = $terakhir ? ((int) substr($terakhir, strlen($prefix))) + 1 : 1;

        return $prefix . str_pad((string) $nomor, $lebar, '0', STR_PAD_LEFT);
    }
}
