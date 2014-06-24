<?php namespace Admin;

class Posts extends Base
{
    protected $layout = 'layouts.admin';

    public function get()
    {
        return;
    }

    public function getList()
    {
        $posts = \Custom\Post::select(NULL, [
            'limit' => 20,
            'offset' => 0
        ]);
        $data['list'] = &$posts;

        return \View::make('admin/posts/list', $data);
    }

    public function getPending()
    {
        $posts = \Custom\Post::select(NULL, [
            'status' => \Custom\Cnst::NEED_VALIDATION,
            'limit' => 20,
            'offset' => 0
        ]);
        $data['pending'] = &$posts;

        return \View::make('admin/posts/pending', $data);
    }

    public function getValidate()
    {
        $id_post = \Input::get('id_post');
        if (!$id_post)
            return;

        $validation = \Custom\Post\Admin::validate($id_post);
        if (!empty($validation['errors']))
        {
            return \Redirect::to('posts/pending')
                            ->withErrors($create['errors']);
        }

        $success = \Lang::get('post.success.validated');
        return \Redirect::to('posts/pending')->with('flash.notice.success', $success);
    }

    public function getReject()
    {
        $id_post = \Input::get('id_post');
        if (!$id_post)
            return;

        $validation = \Custom\Post\Admin::delete($id_post);
        if (!empty($validation['errors']))
        {
            return \Redirect::to('posts/pending')
                            ->withErrors($create['errors']);
        }

        $success = \Lang::get('post.success.rejected');
        return \Redirect::to('posts/pending')->with('flash.notice.success', $success);
    }
}
