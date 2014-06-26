<?php

class SearchController extends BaseController
{
    public function get_Run()
    {
        $queries = [
            'cities' => Input::get('cities'),
            'provinces' => Input::get('provinces'),
            'states' => Input::get('states'),
        ];

        $parsed = \Custom\Geo::string_to_ids($queries);
        if ($parsed['need_301'] === TRUE)
        {
            $query = http_build_query(array_merge(Input::all(), $parsed['params']));
            return \Redirect::to('/search/?' . $query, 301);
        }

        $elastic = new \Custom\Elastic\Post;
        $results = $elastic->search([
            'id_state' => $parsed['data']['states'],
            'id_province' => $parsed['data']['provinces'],
            'id_city' => $parsed['data']['cities'],
            'limit' => 50
        ]);

        $data = [
            'posts' => $results['results'],
            'meta' => $results['meta'],
            'search_title' => implode(', ', $parsed['title']),
            '__map' => [
                'cluster' => TRUE,
                'locate' => TRUE,
                'markers' => $results['markers']
            ]
        ];

        return View::make('default/search/main', $data);
    }

    public function save($id_search = NULL)
    {
        # code...
    }

    public function delete($id_search)
    {
        # code...
    }
}
