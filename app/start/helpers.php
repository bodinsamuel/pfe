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
