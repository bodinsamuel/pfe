<?php namespace ApiV1;

class Search extends \BaseController
{
    public function getPosts()
    {
        $response = new \Custom\ApiResponse;

        // do search
        $elastic = new \Custom\Elastic\Post;
        $search = $elastic->search(\Input::all());

        if (!is_array($search))
        {
            return $response->error(400, 'bad_request');
        }
        else
        {
            $response->meta = $search['meta'];
            $response->results = $search['results'];
            return $response->send($search);
        }
    }
}
