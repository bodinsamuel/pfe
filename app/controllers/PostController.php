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

        return View::make('default/post/create', $data);
    }

    public function post_create()
    {
        $inputs = Input::all();

        $create = Custom\Post::create($inputs);

        if (!is_array($create) || !empty($create['errors']))
        {
            return Redirect::to('post/create')->withInput()
                            ->withErrors($create['errors']);
        }
        else
        {
            $success = Lang::get('post.success.creation');
            return Redirect::to('/post/' . $create['inputs']['id_post'] . '/bla')->with('flash.notice.success', $success);
        }
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
