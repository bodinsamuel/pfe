<?php namespace Services\Mediaserver;

class Get extends \BaseController
{
    protected $layout = NULL;

    static public $allowed_ratio = [
        '75x75' => ['75', '75', '1'],
        '150x100' => ['150', '100', '1'],
        '250x175' => ['250', '175', '1'],
        'cover_post' => ['630', '250', '1']
    ];

    public function get($inputs = NULL)
    {
        if ($inputs === NULL)
            \App::abort(403, 'Unauthorized action.');

        $url_regex = '/([A-z0-9]+)-(original|[0-9]+x[0-9]+|[a-z_]+)-([0-9]+)(-([A-z0-9-]+))?.([a-z0-9]{2,5})/i';
        $matched = preg_match($url_regex, $inputs, $matchs);

        if ($matched === 0 || count($matchs) < 6)
            \App::abort(404);

        // Prepare arguments
        $ratio = NULL;
        $hash = $matchs[1];
        $size = $matchs[2];
        $id_media = (int)$matchs[3];
        $title = $matchs[5];
        $extension = $matchs[6];

        if ($size !== 'original')
        {
            if (!isset(self::$allowed_ratio[$size]))
                \App::abort(404);

            $ratio = self::$allowed_ratio[$size];
        }

        if ($hash === '404' && $title === 'not-found' && $id_media === 0
            && $extension === 'jpg')
        {
            $path = '../public/assets/img/404-'. $size .'.jpg';
            return self::response(404, 'image/jpeg', $path, TRUE);
        }

        $media = \Custom\Media::select($id_media);
        if (empty($media))
            return self::response(404);

        $media = $media[0];
        if ($media->status <= \Custom\Cnst::DELETED)
            return self::response(404, $media->mime);

        // Wrong url
        if ($hash != $media->hash
            || $title != $media->title
            || $extension != $media->extension)
        {
            return self::redirect($media, $size, $media->extension);
        }

        // Get path
        $dir1 = substr($hash, 0, 3);
        $dir2 = substr($hash, 3, 3);
        $dir3 = substr($hash, 6, 3);
        $dir = \Config::get('app.media_dir') . '/' . $dir1  . '/' . $dir2 . '/' . $dir3 . '/' . $hash;


        // Display
        $path = $dir . '/' . $ratio[0] . 'x' . $ratio[1] . '.' . $extension;
        $is_file = is_file($path);

        if ($size === 'original' && !$is_file)
        {
            return self::response(404, $media->mime);
        }
        elseif ($size !== 'original' && !$is_file && $media->type == \Custom\Media::TYPE_IMAGE)
        {
            $resizer = new \Custom\Media\Resizer;
            $resizer->setSource($dir . '/original.' . $extension);
            $resizer->setExtension($extension);
            $resizer->filler($ratio[0], $ratio[1]);

            return self::response(200, $media->mime, $path);
        }
        else
        {
            return self::response(200, $media->mime, $path);
        }
    }

    private static function response($status, $mime = NULL, $path = NULL, $force_404 = FALSE)
    {
        if ($force_404 === TRUE)
        {
            $response = \Response::make(file_get_contents($path), $status);
            $response->header('Content-Type', $mime);
            return $response;
        }

        $internal = '/media_server';
        if ($force_404 === FALSE && $status === 404)
            $internal .= '_404';

        $accel_redirect = str_replace(\Config::get('app.media_dir'), $internal, $path);

        // Make response
        $response = \Response::make('', $status);
        $response->header('X-Accel-Redirect', $accel_redirect);
        if ($mime != NULL)
            $response->header('Content-Type', $mime);

        return $response;
    }

    private static function redirect($media, $ratio, $extension)
    {
        $query = $media->hash . '-' . $ratio . '-' . $media->id_media  . '-' . $media->title . '.' . $extension;
        return \Redirect::to($query, 302);
    }
}
