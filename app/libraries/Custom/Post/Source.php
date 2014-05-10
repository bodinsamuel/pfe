<?php namespace Custom\Post;

class Source
{
    public static function upsert($id_post, $name, $id_source)
    {
        $values = [
            'id_post'   => $id_post,
            'name'      => $name,
            'id_source' => $id_source,
        ];

        // Query
        $query = 'INSERT INTO posts_has_source
                              (`id_post`, `id_source`, `name`)
                       VALUES (:id_post, :id_source, :name)';

        $stmt = \DB::statement($query, $values);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }


    public static function has($ids, $name = NULL)
    {
        $ids = (array)$ids;
        if ($name === NULL)
        {
            $where = 'id_post IN (' . \Custom\Helper\DB::escape_ints($ids) . ')';
            $from = 'id_post';
        }
        else
        {
            $where = 'id_source IN (' . \Custom\Helper\DB::escape_strings($ids) . ')';
            $from = 'id_source';
        }

        $query = 'SELECT id_post, id_source
                    FROM posts_has_source
                   WHERE ' . $where;

        $results = \DB::select($query);
        $results = \Custom\Helper\DB::raw_to_idbased($results, $from);

        $has = [];
        foreach ($ids as $id)
        {
            $has[$id] = (isset($results[$id])) ? TRUE : FALSE;
        }
        return $has;
    }
}
