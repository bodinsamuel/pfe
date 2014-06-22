<?php namespace Custom;

class Address
{
    /**
     * Get address based
     * @param  uint $id_address
     * @param  uint $id_user
     * @return array
     */
    public static function get($id_address = NULL, $id_user = NULL)
    {
        $prepared = [];
        $where = [];
        if ($id_address > 0)
        {
            $where[] = 'addr.id_address = :id_address';
            $prepared['id_address'] = $id_address;
        }
        if ($id_user > 0)
        {
            $where[] = 'addr.id_user = :id_user';
            $prepared['id_user'] = $id_user;
        }

        $query = 'SELECT addr.id_address,
                         cities.id_city,
                         cities_districts.id_district,
                         countries.id_country,
                         streets_type.id_street_type
                    FROM addresses AS addr
               LEFT JOIN geo_cities AS cities
                         ON cities.id_city = addr.id_city
               LEFT JOIN geo_cities_districts AS cities_districts
                         ON cities_districts.id_district = addr.id_district
               LEFT JOIN geo_countries AS countries
                         ON cities.id_country = countries.id_country
               LEFT JOIN geo_streets_type AS streets_type
                         ON streets_type.id_street_type = addr.id_street_type
                   WHERE ' . implode(' AND ', $where);

        return \DB::select($query, $prepared);
    }

    /**
     * Update or Insert address in table
     * @param  array $inputs
     * @param  uint  $id_address
     * @return uint
     */
    public static function upsert($inputs, $id_address = NULL)
    {
        $inputs = array_fill_base([
            'id_city', 'address1', 'address2', 'longitude', 'latitude', 'primary', 'origin'
        ], $inputs);

        $inputs['id_address'] = ($id_address === NULL) ? NULL : $id_address;
        $inputs['id_user'] = \User::getIdOrZero();

        // Query
        $query = 'INSERT INTO addresses
                              (id_address, id_user, id_city, address1,
                               `address2`, `longitude`, `latitude`, `primary`,
                               `origin`, date_created, date_updated)
                       VALUES (:id_address, :id_user, :id_city, :address1,
                               :address2, :longitude, :latitude, :primary,
                               :origin, NOW(), NOW())
             ON DUPLICATE KEY
                       UPDATE `id_city`      = VALUES(`id_city`),
                              `address1`     = VALUES(`address1`),
                              `address2`     = VALUES(`address2`),
                              `longitude`    = VALUES(`longitude`),
                              `latitude`     = VALUES(`latitude`),
                              `primary`      = VALUES(`primary`),
                              `date_updated` = VALUES(`date_updated`)';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }

    public static function validate($inputs)
    {
        return \Validator::make(
            $inputs, [
                'id_address'  => 'integer',
                'id_city'     => 'required|integer|min:1',
                'address1'    => 'required',
                'longitude'   => 'numeric',
                'latitude'    => 'numeric',
                'origin'      => 'required'
            ]
        );
    }
}
