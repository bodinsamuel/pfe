<?php

class SearchController extends BaseController
{
    public function get_Run()
    {
        $data = ['__with_bg_map' => TRUE,
                 'map_config' => [
                    'locate' => TRUE,
                    'cluster' => TRUE
                ]];

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

        $data['meta'] = $results['meta'];
        $data['posts'] = $results['results'];
        $data['__map_markers'] = $results['markers'];
        // $data['__map_markers_center'] = $results['markers_center'];

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
