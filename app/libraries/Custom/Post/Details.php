<?php namespace Custom\Post;

class Details
{
    public static function insert($inputs)
    {
        $inputs = array_only($inputs, [
            'surface_living'
        ]);

        // Query
        $query = 'INSERT INTO posts_details
                              (surface_living)
                       VALUES (:surface_living)';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }

    public static function validate($inputs)
    {
        return \Validator::make(
            $inputs, [
                'bathroom' => 'integer',
                // 'wc' => 'boolean',
                // 'garage' => 'integer',
                // 'balcony' => 'boolean'
            ]
        );
    }
}
