<?php

namespace App\Helpers;

class Arabic
{
    /**
     * Fixes Arabic text for display in domPDF.
     *
     * @param string $string The text to be fixed.
     * @return string The fixed text.
     */
    public static function fixPDF(?string $string): string
    {
        if ($string === null) {
            return '';
        }

        $arabic = new \ArPHP\I18N\Arabic();
        $p = $arabic->arIdentify($string);

        for ($i = count($p) - 1; $i >= 0; $i -= 2) {
            $utf8ar = $arabic->utf8Glyphs(substr($string, $p[$i-1], $p[$i] - $p[$i-1]));
            $string = substr_replace($string, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
        }

        return $string;
    }
}