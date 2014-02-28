<?php

class PostController extends BaseController {

    public function one($id_post)
    {
        return View::make('post/one');
    }
}
