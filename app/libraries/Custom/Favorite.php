<?php namespace Custom;

class Favorite
{
    public function create()
    {
        # code...
    }

    public static function select($opts, $offset = 0, $limit = 20)
    {
        $where = [];
        if (isset($opts['id_favorite']))
            $where[] = 'id_favorite = ' . (int)$opts['id_favorite'];
        if (isset($opts['id_user']))
            $where[] = 'id_user = ' . (int)$opts['id_user'];
        if (isset($opts['id_post']))
            $where[] = 'id_post = ' . (int)$opts['id_post'];

        // Query
        $query = 'SELECT id_favorite,
                         id_post,
                         date_action
                    FROM favorites
                   WHERE ' . implode(' AND ', $where) . '
                ORDER BY date_action DESC
                   LIMIT ' . $offset . ', ' . $limit;

        $select = \DB::select($query);
        if (empty($select))
            return [];

        $ids_posts = [];
        $final = [];
        foreach ($select as $values)
        {
            $final[$values->id_post] = $values;
            $ids_posts[] = $values->id_post;
        }

        $elastic = new \Custom\Elastic\Post;
        $posts = $elastic->search([
            '_id' => $ids_posts
        ]);

        return [
            'ids_posts' => $ids_posts,
            'favorites' => $final,
            'linked' => $posts
        ];
    }
}
