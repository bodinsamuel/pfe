<?php namespace Custom;

class Media
{
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_AUDIO = 3;
    const TYPE_OTHER = 4;

    public static function create($inputs)
    {
        $inputs = array_only($inputs, [
            'id_gallery', 'type', 'mime', 'hash'
        ]);
        $inputs['status'] = Cnst::NEED_VALIDATION;
        $inputs['id_user'] = \User::getIdOrZero();

        $query = 'INSERT INTO media
                              (`id_gallery`, `id_user`, `type`, `mime`, `hash`,
                               `status`, `date_created`, `date_updated`)
                       VALUES (:id_gallery, :id_user, :type, :mime, :hash,
                               :status, NOW(), NOW())';

        $stmt = \DB::statement($query, $inputs);
        if ($stmt === FALSE)
            return -1;

        $update_gallery = Gallery::update_media_count();

        return \DB::getPdo()->lastInsertId();
    }

    public static function validate($inputs)
    {
        return \Validator::make(
            $inputs, [
                'id_gallery' => 'required|integer',
                'type' => 'required|integer',
                'mime' => 'required|string',
                'hash' => 'required|string',
            ]
        );
    }

    public static function upload($id_gallery, array $medias)
    {
        $return = ['failed' => FALSE, 'errors' => [], 'data' => []];

        $uploader = new Media\Uploader();
        $uploader->setAllowed(['image/jpeg', 'image/png']);
        $uploader->setDir('/var/www/pfe.dev/media');
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
                    'type' => $up['infos']['type'],
                    'mime' => $up['infos']['mime'],
                    'hash' => $up['hash']
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
}
