<?php namespace Custom\Post;

class Price
{
    public static function get_current($id_post)
    {
        $query = 'SELECT price
                    FROM posts
                   WHERE id_post = :id_post';

        return \DB::select($query, ['id_post' => (int)$id_post]);
    }

    public static function get_history($id_post)
    {
        $query = 'SELECT id_post, value, trend
                    FROM posts_price_history
                   WHERE id_post = :id_post
                ORDER BY date_created DESC';

        return \DB::select($query, ['id_post' => (int)$id_post]);
    }

    public static function insert($id_post, $price)
    {
        // Get post
        $current = Price::get_current($id_post)[0]->price;

        // Insert new value with trend
        if ($current == 0 || $current == $price)
            $trend = 0;
        elseif ($current > $price)
            $trend = -1;
        else
            $trend = 1;

        $inputs = [
            'id_post' => $id_post,
            'price' => (double)$price,
            'trend' => $trend
        ];
        $query = 'INSERT INTO posts_price_history
                              (`id_post`, `price`, `trend`, `date_created`)
                       VALUES (:id_post, :price, :trend, NOW())';
        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        $lid = \DB::getPdo()->lastInsertId();

        // Insert new value in post
        $query = 'UPDATE posts
                    SET price = :price
                  WHERE id_post = :id_post';

        $inputs = [
            'id_post' => $id_post,
            'price' => (double)$price
        ];
        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return $stmt;
    }
}
