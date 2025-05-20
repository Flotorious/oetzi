<?php

namespace App\Utils;

class ColorHelper
{
    public static function generateColorFromString(string $input, float $alpha = 0.6): string
    {
        $hash = md5($input);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        return "rgba($r, $g, $b, $alpha)";
    }
}
