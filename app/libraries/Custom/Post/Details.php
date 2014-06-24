<?php namespace Custom\Post;

class Details
{
    const CONDITION_NEW = 1;
    const CONDITION_USED = 2;

    public static function insert($inputs)
    {
        $inputs = array_fill_base([
            'surface_living', 'room', 'condition', 'bathroom', 'wc',
            'garage', 'balcony'
        ], $inputs);

        // Query
        $query = 'INSERT INTO posts_details
                              (surface_living, room, `condition`, bathroom, wc,
                               garage, balcony)
                       VALUES (:surface_living, :room, :condition, :bathroom, :wc,
                               :garage, :balcony)';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }

    public static function validate($inputs)
    {
        return \Validator::make(
            $inputs, [
                'surface_living' => 'required|integer',
                'room' => 'required|integer',
                'condition' => 'integer',
                'bathroom' => 'integer',
                'wc' => 'integer',
                'garage' => 'integer',
                'balcony' => 'boolean'
            ]
        );
    }
}
