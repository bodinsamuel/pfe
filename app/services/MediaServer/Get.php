<?php

class MediaServer_Get extends BaseController
{
    static public $allowed_ratio = [
        '50x50',
        '100x100'
    ];

    public function get($inputs = NULL)
    {
        if ($inputs === NULL)
            App::abort(403, 'Unauthorized action.');

        $url_regex = '/([A-z0-9]+)-(original|[0-9]+x[0-9]+)-([0-9]+)-([A-z0-9-]+).([a-z0-9]{2,5})/i';
        $matched = preg_match($url_regex, $inputs, $matchs);

        if ($matched === 0 || count($matchs) != 6)
            App::abort(404);

        // Prepare arguments
        $ratio = NULL;
        $hash = $matchs[1];
        $size = $matchs[2];
        $id_media = (int)$matchs[3];
        $title = $matchs[4];
        $extension = $matchs[5];

        if ($size !== 'original')
        {
            if (!in_array($size, self::$allowed_ratio))
                App::abort(404);

            $ratio = explode('x', $size);
        }

        $media = Custom\Media::select($id_media);
        if (empty($media))
            return self::response(404);

        $media = $media[0];
        if ($media->status <= Custom\Cnst::DELETED)
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
        $dir = Custom\Media::UPLOAD_DIR . '/' . $dir1  . '/' . $dir2 . '/' . $dir3 . '/' . $hash;

        // Display
        $path = $dir . '/' . $size . '.' . $extension;
        $is_file = is_file($path);

        if ($size === 'original' && !$is_file)
        {
            return self::response(404, $media->mime);
        }
        elseif ($size !== 'original' && !$is_file)
        {
            die('need to create a ratio');
        }
        else
        {
            return self::response(200, $media->mime, $path);
        }
    }

    private static function response($status, $mime = NULL, $path = NULL)
    {
        $internal = '/media_server';
        if ($status === 404)
            $internal .= '_404';

        $accel_redirect = str_replace(Custom\Media::UPLOAD_DIR, $internal, $path);

        // Make response
        $response = Response::make('', $status);
        $response->header('X-Accel-Redirect', $accel_redirect);
        if ($mime != NULL)
            $response->header('Content-Type', $mime);

        return $response;
    }

    private static function redirect($media, $ratio, $extension)
    {
        $query = $media->hash . '-' . $ratio . '-' . $media->id_media  . '-' . $media->title . '.' . $extension;
        return Redirect::to($query, 302);
    }

    public function getError()
    {
        var_dump('prout');
        return;
    }
}
