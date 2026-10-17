<?php

namespace App\Exceptions;

use RuntimeException;

/** Dilempar saat perubahan status produksi melanggar business rule (BR-08, BR-09). */
class StatusProduksiException extends RuntimeException
{
    public function __construct(string $message, public array $rincian = [])
    {
        parent::__construct($message);
    }
}
