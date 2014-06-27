<?php

class PostController extends BaseController
{

    public function getOne($id_post, $slug = NULL)
    {
        $posts = \Custom\Post::select([$id_post], [
            'limit' => 1,
        ]);

        // 404 not found
        if ($posts['count'] == 0)
            return App::abort(404);

        $post = $posts['posts'][$id_post];

        // 301 found but wrong slug
        if ($slug != $post->slug)
            return Redirect::to($post->url);

        $data = [
            '__page_title' => $post->title,
            '__map' => [
                'cluster' => TRUE,
                'markers' => $posts['markers'],
                'center' => $posts['markers'][$post->id_post],
                'static' => TRUE,
                'openOnLoad' => TRUE
            ],
            'post' => $post,
        ];

        return View::make('default/post/display', $data);
    }

    public function getCreate()
    {
        $data = ['__page_title' => 'Create Post'];

        return View::make('default/post/create', $data);
    }

    public function postCreate()
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
