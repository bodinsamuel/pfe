<?php namespace ApiV1;

class Autocomplete extends \BaseController
{
    public function getLocation()
    {
        $q = \Input::get('q');
        if (!$q)
        {
            return \Response::json([
                'time' => time(),
                'error' => 'bad_request',
                'data' => []
            ], 400);
        }

        // Query
        $cities = \Custom\Geo::search_cities($q);
        $provinces = \Custom\Geo::search_provinces($q);
        $states = \Custom\Geo::search_states($q);

        return \Response::json([
            'time' => time(),
            'error' => FALSE,
            'data' => [
                [ 'name' => 'States', 'children' => $states ],
                [ 'name' => 'Provinces', 'children' => $provinces ],
                [ 'name' => 'Cities', 'children' => $cities ]
            ]
        ], 200);
    }
}
