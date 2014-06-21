<?php namespace Admin;

class Posts extends Base
{
    protected $layout = 'layouts.admin';

    public function get()
    {
        return;
    }

    public function getPending()
    {
        $posts = \Custom\Post::select(NULL, [
            'status' => \Custom\Cnst::NEED_VALIDATION,
            'limit' => 20,
            'offset' => 0
        ]);
        return;
    }
}
