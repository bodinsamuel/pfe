<?php namespace Custom\Gallery;

class Admin
{
    public static function validate($id_gallery)
    {
        return self::status($id_gallery, \Custom\Cnst::VALIDATED);
    }

    public static function delete($id_gallery)
    {
        return self::status($id_gallery, \Custom\Cnst::DELETED);
    }

    public static function status($id_gallery, $status)
    {
        $query = 'UPDATE galleries
              INNER JOIN media
                         ON media.id_gallery = galleries.id_gallery
                     SET galleries.status = ' . (int)$status . ',
                         media.status = ' . (int)$status . '
                   WHERE galleries.id_gallery = ' . (int)$id_gallery;

        $post = \DB::statement($query);
        if ($post === FALSE)
            return -1;

        return TRUE;
    }
}
