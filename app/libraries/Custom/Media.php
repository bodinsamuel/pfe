<?php namespace Custom;

class Media
{
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_AUDIO = 3;
    const TYPE_OTHER = 4;

    const URL_ACCESS = 'http://media.pfe.dev';
    const UPLOAD_DIR = '/var/www/pfe.dev/media';

    public static function create($inputs)
    {
        $inputs = array_fill_base([
            'id_gallery', 'type', 'extension', 'mime', 'hash', 'title',
            'width', 'height'
        ], $inputs);
        $inputs['status'] = Cnst::NEED_VALIDATION;
        $inputs['id_user'] = \User::getIdOrZero();
        $inputs['title'] = \Str::slug($inputs['title']);

        $query = 'INSERT INTO media
                              (`id_gallery`, `id_user`, `type`, `extension`, `mime`,
                               `hash`, `width`, `height`, `title`, `status`,
                               `date_created`, `date_updated`)
                       VALUES (:id_gallery, :id_user, :type, :extension, :mime,
                               :hash, :width, :height, :title, :status,
                               NOW(), NOW())';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        $update_gallery = Gallery::auto_update($inputs['id_gallery']);

        return \DB::getPdo()->lastInsertId();
    }

    public static function validate($inputs)
    {
        return \Validator::make(
            $inputs, [
                'id_gallery' => 'required|integer',
                'type'       => 'required|integer',
                'extension'  => 'required|string',
                'mime'       => 'required|string',
                'hash'       => 'required|string',
                'title'      => 'required|string',
                'width'       => 'required|integer',
                'height'      => 'required|integer'
            ]
        );
    }

    public static function select($id_media)
    {
        $query = 'SELECT id_media,
                         id_user,
                         type,
                         extension,
                         mime,
                         hash,
                         title,
                         status,
                         date_created,
                         date_updated
                    FROM media
                   WHERE id_media = :id_media';

        return \DB::select($query, ['id_media' => $id_media]);
    }

    public static function upload($id_gallery, array $medias)
    {
        $return = ['failed' => FALSE, 'errors' => [], 'data' => []];

        $uploader = new Media\Uploader();
        $uploader->setAllowed(['image/jpeg', 'image/png']);
        $uploader->setDir(self::UPLOAD_DIR);
        $uploader->setMaxFileSize(1024 * 1024 * 10);

        foreach ($medias as $media)
        {
            // upload
            if (isset($media['url']))
                $up = $uploader->handleUrl($media['url']);
            else
                $up = $uploader->handle($media->getPathname());

            // Check error
            if (is_int($up) && $up < 0)
            {
                $return['failed'] = TRUE;
                $return['errors'][] = ['upload', $up];
            }
            else
            {
                // Creation media in DB
                $creation = Media::create([
                    'id_gallery' => $id_gallery,
                    'title' => $media['title'],
                    'type' => $up['infos']['type'],
                    'mime' => $up['infos']['mime'],
                    'width' => $up['infos']['width'],
                    'height' => $up['infos']['height'],
                    'hash' => $up['hash'],
                    'extension' => $up['infos']['ext_safe']
                ]);

                // Check Error
                if ($creation < 0)
                {
                    $return['failed'] = TRUE;
                    $return['errors'][] = ['creation', $creation];
                }
                else
                {
                    $return['data'][] = $creation;
                }
            }
        }

        return $return;
    }

    public static function url($m, $ratio)
    {
        if (empty($m))
            return self::URL_ACCESS . '/404-'. $ratio . '-0-not-found.jpg';

        $m = (array)$m;
        return self::URL_ACCESS . '/' . $m['hash'] . '-' . $ratio . '-' .
               $m['id_media'] . '-' . $m['title'] . '.' . $m['extension'];
    }
}
