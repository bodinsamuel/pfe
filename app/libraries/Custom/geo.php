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
        $query = 'SELECT name
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
    public static function search_cities($search)
    {
        $query = 'SELECT name, zipcode
                    FROM geo_cities
                   WHERE name LIKE "' . $search . '%"
                         OR zipcode LIKE "' . $search . '%"
                   LIMIT 0, 10';

        return \DB::select($query);
    }
}
