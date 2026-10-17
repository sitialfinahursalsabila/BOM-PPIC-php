<?php

namespace App\Support;

class Angka
{
    /** 120.00 menjadi "120", 1.5 menjadi "1,5". */
    public static function fmt($nilai): string
    {
        $teks = number_format((float) $nilai, 2, ',', '.');
        return rtrim(rtrim($teks, '0'), ',');
    }

    public static function rupiah($nilai): string
    {
        return 'Rp ' . number_format((float) $nilai, 0, ',', '.');
    }
}
