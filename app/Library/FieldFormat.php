<?php

namespace App\Library;


class FieldFormat
{
    public static function hideLength(string $value, int $len = 10) : string
    {
        return mb_substr($value, 0, $len);
    }

    public static function cameMark(string $value) : string
    {
        return StrParse::underToCame($value);
    }

    public static function datetimeOnlyDate(string $date) : string
    {
        return substr($date,0,10);
    }
}