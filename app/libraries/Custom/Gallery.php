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
}
