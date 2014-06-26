<?php namespace Custom\Post;

class Price
{
    const ALL_INCLUSIVE = 1;
    const NOT_INCLUDED = 2;

    public static $type = [
        1 => 'cc',
        2 => 'ht'
    ];

    public static function get_current($id_post)
    {
        $query = 'SELECT price, price_type
                    FROM posts
                   WHERE id_post = :id_post';

        return \DB::select($query, ['id_post' => (int)$id_post]);
    }

    public static function get_history($id_post)
    {
        $query = 'SELECT id_post, value, type, trend
                    FROM posts_price_history
                   WHERE id_post = :id_post
                ORDER BY date_created DESC';

        return \DB::select($query, ['id_post' => (int)$id_post]);
    }

    public static function insert($id_post, $price, $type)
    {
        // Get post
        $current = Price::get_current($id_post)[0];

        // Insert new value with trend
        if ($current->price == 0 || $current->price == $price)
            $trend = 0;
        elseif ($current->price > $price)
            $trend = -1;
        else
            $trend = 1;

        $inputs = [
            'id_post' => $id_post,
            'price' => (double)$price,
            'type' => (int)$type,
            'trend' => $trend
        ];
        $query = 'INSERT INTO posts_price_history
                              (`id_post`, `price`, `type`, `trend`, `date_created`)
                       VALUES (:id_post, :price, :type, :trend, NOW())';
        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        $lid = \DB::getPdo()->lastInsertId();

        // Insert new value in post
        $query = 'UPDATE posts
                    SET price = :price,
                        price_type = :price_type
                  WHERE id_post = :id_post';

        $inputs = [
            'id_post' => $id_post,
            'price' => (double)$price,
            'price_type' => (int)$type
        ];
        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return $stmt;
    }
}
