<?php namespace Custom;

class Acl
{
    private static $tree = [
        'root' => [
            'moderator' => 1,
            'user' => 1,
        ],
        'moderator' => [ 'user' => 1 ]
    ];

    public static function isAtLeast($name)
    {
        $current =  Session::get('acl_name');
        if ($current === FALSE)
            return FALSE;

        if ($name === $current || isset(self::$tree[$name]))
            return TRUE

        return FALSE;
    }
}
