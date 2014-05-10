<?php namespace Custom\Helper;

class DB
{
    public static function escape_ints(array $ints)
    {
        return implode(',', array_map(function($n) {
            return (int)$n;
        }, $ints));
    }

    public static function escape_strings(array $strings)
    {
        $pdo = \DB::connection()->getPdo();
        return implode(',', array_map(function($n) use ($pdo) {
            return $pdo->quote($n);
        }, $strings));
    }

    public static function raw_to_idbased($array, $name)
    {
        $results = [];
        foreach ($array as $key => $value)
        {
            $results[$value->$name] = $value;
        }
        return $results;
    }
}
