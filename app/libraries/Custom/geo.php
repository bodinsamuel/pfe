<?php namespace Custom;

class Geo
{
    /**
     * Search states
     * @param  string $search
     * @return array
     */
    public static function search_states($search, $id_countries = NULL, $type = NULL)
    {
        $where = [];

        if ($id_countries !== NULL)
            $where[] = 'id_country IN (' . implode(',', (array)$id_countries) .')';

        if ($type === 'ids')
            $where[] = 'id_state IN (' . implode(',', (array)$search) .')';

        $whereor = [];
        if ($type === NULL || $type === 'name')
            $whereor[] = 'name LIKE "' . $search . '%"';

        if (!empty($whereor))
            $where[] = '(' . implode(' OR ', $whereor) . ')';

        $query = 'SELECT id_state AS id, name,
                         CONCAT(safe, "-", id_state) AS url,
                         "states" AS type
                    FROM geo_states
                   WHERE ' . implode(' AND ', $where) . '
                   LIMIT 0, 5';

        return \DB::select($query);
    }

    /**
     * Search provinces
     * @param  string $search
     * @return array
     */
    public static function search_provinces($search, $id_countries = NULL, $type = NULL)
    {
        $where = [];

        if ($id_countries !== NULL)
            $where[] = 'id_country IN (' . implode(',', (array)$id_countries) .')';

        if ($type === 'ids')
            $where[] = 'id_province IN (' . implode(',', (array)$search) .')';

        $whereor = [];
        if ($type === NULL || $type === 'name')
            $whereor[] = 'name LIKE "' . $search . '%"';
        if ($type === NULL || $type === 'iso1')
            $whereor[] = 'iso1 LIKE "' . $search . '%"';

        if (!empty($whereor))
            $where[] = '(' . implode(' OR ', $whereor) . ')';

        $query = 'SELECT id_province AS id, name,
                         CONCAT(safe, "-", id_province) AS url,
                         "provinces" AS type
                    FROM geo_provinces
                   WHERE ' . implode(' AND ', $where) . '
                   LIMIT 0, 5';

        return \DB::select($query);
    }

    /**
     * Search cities
     * @param  string $search
     * @return array
     */
    public static function search_cities($search, $id_countries = NULL, $type = NULL)
    {
        $where = [];

        if ($id_countries !== NULL)
            $where[] = 'id_country IN (' . implode(',', (array)$id_countries) .')';

        if ($type === 'ids')
            $where[] = 'id_city IN (' . implode(',', (array)$search) .')';

        $whereor = [];
        if ($type === NULL || $type === 'name')
            $whereor[] = 'name LIKE "' . $search . '%"';
        if ($type === NULL || $type === 'zipcode')
            $whereor[] = 'zipcode LIKE "' . $search . '%"';

        if (!empty($whereor))
            $where[] = '(' . implode(' OR ', $whereor) . ')';

        $query = 'SELECT id_city AS id, CONCAT(name, ", ", zipcode) AS name,
                         CONCAT(safe, "-", id_city) AS url,
                         "cities" AS type
                    FROM geo_cities
                   WHERE ' . implode(' AND ', $where) . '
                   LIMIT 0, 5';

        return \DB::select($query);
    }

    public static function get_id_city_from_zipcode($zipcode, $country = "FR")
    {
        static $cache;

        if (isset($cache[$zipcode]))
            return $cache[$zipcode];

        $query = 'SELECT id_city
                    FROM geo_cities
               LEFT JOIN geo_countries
                         ON geo_cities.id_country = geo_countries.id_country
                         AND geo_countries.iso2 = ?
                   WHERE geo_cities.zipcode = ?
                   LIMIT 1';

        $result = \DB::select($query, [$country, $zipcode]);
        if (empty($result))
            return FALSE;

        $cache[$zipcode] = $result[0]->id_city;
        return $cache[$zipcode];
    }

    public static function string_to_ids($queries)
    {
        if (empty($queries))
            return FALSE;

        $parsed = [
            'cities' => ['ids' => [], 'items' => []],
            'states' => ['ids' => [], 'items' => []],
            'provinces' => ['ids' => [], 'items' => []]
        ];

        $return = [
            'need_301' => FALSE,
            'params' => [
                'cities' => [],
                'states' => [],
                'provinces' => [],
            ],
            'data' => [
                'cities' => [],
                'states' => [],
                'provinces' => [],
            ]
        ];

        if (empty($queries['cities']) && empty($queries['provinces']) && empty($queries['states']))
            return $return;

        $error = FALSE;
        foreach ($queries AS $type => $query)
        {
            if (!isset($parsed[$type]))
                continue;

            $split = explode(',', $query);
            foreach ($split as $string)
            {
                $rpos = strrpos($string, '-');

                // This string very wrong
                if ($rpos === FALSE)
                {
                    $error = TRUE;
                    continue;
                }

                $name = substr($string, 0, $rpos);
                $id = (int)substr($string, $rpos+1);

                $parsed[$type]['ids'][] = $id;
                $parsed[$type]['items'][$id] = [
                    'id'   => $id,
                    'name' => $name,
                    'url'  => $string
                ];
            }

            if (!empty($parsed[$type]['ids']))
            {
                $results = call_user_func('self::search_' . $type, $parsed[$type]['ids'], NULL, 'ids');

                foreach ($results as $value)
                {
                    $return['data'][$value->type][] = $value->id;
                    $return['params'][$value->type][] = $value->url;
                }
            }

            // Check if array differ
            if (count($return['params'][$type]) != count($parsed[$type]['ids']))
                $return['need_301'] = TRUE;

            $return['params'][$type] = implode(',', $return['params'][$type]);
        }

        return $return;
    }

    public static function get_center_of_geocoord($markers)
    {
        $num_coords = count($markers);

        $X = 0.0;
        $Y = 0.0;
        $Z = 0.0;

        foreach ($markers as $coord)
        {
            $lat = $coord['y'] * pi() / 180;
            $lon = $coord['x'] * pi() / 180;

            $a = cos($lat) * cos($lon);
            $b = cos($lat) * sin($lon);
            $c = sin($lat);

            $X += $a;
            $Y += $b;
            $Z += $c;
        }

        $X /= $num_coords;
        $Y /= $num_coords;
        $Z /= $num_coords;

        $lon = atan2($Y, $X);
        $hyp = sqrt($X * $X + $Y * $Y);
        $lat = atan2($Z, $hyp);

        return [
            'lat' => $lat * 180 / pi(),
            'lon' => $lon * 180 / pi()
        ];
    }
}
