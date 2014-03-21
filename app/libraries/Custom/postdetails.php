<?php namespace Custom;

class PostDetails
{
    public static function insert($inputs)
    {
        $inputs = array_only($inputs, [
            'id_post_type'
        ]);

        // Query
        $query = 'INSERT INTO posts_details
                              (id_post_type)
                       VALUES (:id_post_type)';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }

    public static function validate($inputs)
    {
        return \Validator::make(
            $inputs, [
                'id_post_type' => 'required|integer'
            ]
        );
    }
}
