<?php

/**
 * Shorthand: Redirect to wanted route with a custom error msg.
 * @param  string $route
 * @param  string $msg
 */
function oops($route, $msg = 'global.error.oops')
{
    $error = $msg !== NULL ? Lang::get($msg) : NULL;
    return Redirect::to($route)->withInput()->with('flash.notice.error', $error);
}

function array_fill_base($base = [], $array)
{
    $_base = array_flip($base);
    $match = array_intersect_key($array, $_base);
    $base = array_fill_keys($base, '');
    return array_merge($base, $match);
}

Validator::extend('boolean', function($attribute, $value, $parameters)
{
    return is_bool($value);
});
