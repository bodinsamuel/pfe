<?php

class PostController extends BaseController
{

    public function get_one()
    {
        # code...
    }

    public function get_create()
    {
        $data = ['__page_title' => 'Create Post'];
        Custom\Addresses::test();
        return View::make('post/create', $data);
    }

    public function post_create()
    {
        # code...
    }

    public function get_edit()
    {
        # code...
    }

    public function post_edit()
    {
        # code...
    }

    public function get_delete()
    {
        # code...
    }

    public function delete_delete()
    {
        # code...
    }
}
