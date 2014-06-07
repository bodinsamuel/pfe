<?php namespace Custom\Post;

class Details
{
    public static function insert($inputs)
    {
        $inputs = array_only($inputs, [
            'bathroom'
        ]);

        // Query
        $query = 'INSERT INTO posts_details
                              (bathroom)
                       VALUES (:bathroom)';

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
