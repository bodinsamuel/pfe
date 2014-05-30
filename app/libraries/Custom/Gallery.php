<?php namespace Custom;

class Gallery
{
    public static function create($status = 0)
    {
        $inputs['id_user'] = \User::getIdOrZero();
        $inputs['status'] = (int)$status;

        $query = 'INSERT INTO galleries
                              (`id_user`, media_count, `status`, date_created, date_updated)
                       VALUES (:id_user, 0, :status, NOW(), NOW())';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }

    public static function select($id_gallery)
    {
        if (empty($id_gallery))
            throw new \Exception("[GALLERY] empty id_gallery");

        $where[] = 'galleries.status = ' . Cnst::VALIDATED;
        $where[] = 'galleries.id_gallery IN (' . implode(', ', (array)$id_gallery) . ')';

        $query = 'SELECT galleries.id_gallery,
                         galleries.media_count,

                         media.id_media,
                         media.type,
                         media.mime,
                         media.hash,
                         media.date_updated,

                         CONCAT(SUBSTRING(media.hash, 1, 3), "/", SUBSTRING(media.hash, 4, 3), "/", SUBSTRING(media.hash, 7, 3), "/", media.hash) AS path
                    FROM galleries
               LEFT JOIN media
                         ON media.id_gallery = galleries.id_gallery
                            AND media.status = ' . Cnst::VALIDATED . '
                   WHERE ' . implode(' AND ', $where);

        $select = \DB::select($query);
        if (empty($select))
            return [];

        $final = [];
        foreach ($select as &$value)
        {
            if (!isset($final[$value->id_gallery]))
            {
                $final[$value->id_gallery] = [
                    'count' => $value->media_count,
                    'media' => []
                ];
            }

            if ($value->id_media)
                $final[$value->id_gallery]['media'][] = $value;
        }
        return $final;
    }

    public static function update_media_count($id_gallery)
    {
        // PDO is the shit
        $inputs = [$id_gallery, $id_gallery];

        $query = 'UPDATE galleries
                     SET media_count = (SELECT COUNT(1) AS total
                                          FROM media
                                         WHERE id_gallery = ?)
                   WHERE id_gallery = ?
                   LIMIT 1';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }
}
