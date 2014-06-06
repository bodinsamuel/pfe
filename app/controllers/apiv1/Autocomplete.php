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

        return \Response::json([
            'time' => time(),
            'error' => FALSE,
            'data' => [
                'cities' => $cities,
                'provinces' => $provinces
            ]
        ], 200);
    }
}
