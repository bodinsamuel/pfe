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
                         galleries.id_cover,
                         galleries.media_count,

                         media.id_media,
                         media.type,
                         media.extension,
                         media.mime,
                         media.hash,
                         media.title,
                         media.date_updated
                    FROM galleries
               LEFT JOIN media
                         ON media.id_gallery = galleries.id_gallery
                            AND media.status = ' . Cnst::VALIDATED . '
                   WHERE ' . implode(' AND ', $where) . '
                ORDER BY id_media ASC';

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
                    'id_cover' => $value->id_cover,
                    'cover' => [],
                    'media' => []
                ];
            }


            if ($value->id_media)
            {
                if ($value->id_media == $value->id_cover)
                    $final[$value->id_gallery]['cover'] = $value;
                else
                    $final[$value->id_gallery]['media'][] = $value;
            }
        }
        return $final;
    }

    public static function auto_update($id_gallery)
    {
        // Get id_cover
        $query = 'SELECT IF ((id_cover IS NULL OR id_cover = 0), IF (media.id_media IS NULL or media.id_media = 0, 0, media.id_media), id_cover) AS id_cover
                   FROM galleries
              LEFT JOIN media
                        ON media.id_gallery = galleries.id_gallery
                  WHERE galleries.id_gallery = ? AND media.status >= ?
               GROUP BY galleries.id_gallery
               ORDER BY media.date_created ASC';
        $id_cover = \DB::select($query, [$id_gallery, Cnst::NEED_VALIDATION]);
        $id_cover = (empty($id_cover)) ? 'NULL' : $id_cover[0]->id_cover;

        // PDO is the shit
        $inputs = [ $id_gallery, $id_cover, $id_gallery];
        $query = 'UPDATE galleries
                     SET media_count = (SELECT COUNT(1) AS total
                                          FROM media
                                         WHERE id_gallery = ?),
                         id_cover = ?
                   WHERE id_gallery = ?
                   LIMIT 1';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }

    public function update_id_cover($id_gallery, $id_media)
    {
        $inputs = [
            'id_gallery' => $id_gallery,
            'id_media' => $id_media
        ];

        $query = 'UPDATE galleries
                     SET id_cover = :id_media
                   WHERE id_gallery = :id_gallery
                   LIMIT 1';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        return \DB::getPdo()->lastInsertId();
    }
}
