<?php
namespace App\Traits;
trait Randomize {
    static function generateString(): string {
        $bases = implode('', func_get_args()); // . time();
        return sprintf('%08x', crc32($bases));
    }
}
