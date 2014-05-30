<?php namespace Custom;

class Geo
{
    /**
     * Search provinces
     * @param  string $search
     * @return array
     */
    public static function search_provinces($search)
    {
        $query = 'SELECT id_province AS id, name
                    FROM geo_provinces
                   WHERE name LIKE "' . $search . '%"
                   LIMIT 0, 5';

        return \DB::select($query);
    }
    /**
     * Search cities
     * @param  string $search
     * @return array
     */
    public static function search_cities($search, $countries = NULL, $type = NULL)
    {
        $where = [];

        if ($countries !== NULL)
            $where[] = 'iso2 IN ' . implode(', ', (array)$countries);

        $whereor = [];
        if ($type === NULL || $type === 'name')
            $whereor[] = 'name LIKE "' . $search . '%"';
        if ($type === NULL || $type === 'zipcode')
            $whereor[] = 'zipcode LIKE "' . $search . '%"';

        if (!empty($whereor))
            $where[] = '(' . implode(' OR ', $whereor) . ')';

        $query = 'SELECT zipcode AS id, CONCAT(name, ", ", zipcode) AS name,
                         zipcode, id_city, geo_countries.id_country
                    FROM geo_cities
               LEFT JOIN geo_countries
                         ON geo_cities.id_country = geo_countries.id_country
                   WHERE ' . implode(' AND ', $where) . '
                   LIMIT 0, 10';

        return \DB::select($query);
    }
}
