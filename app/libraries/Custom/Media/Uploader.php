<?php namespace Custom\Media;

class Uploader
{
    const BAD_MIME = -1;
    const NO_FILE = -2;
    const NO_HASH = -3;
    const FAILED_CREATE_DIR = -4;
    const FAILED_MOVE = -5;
    const NO_SAFE_EXT = -6;
    const URL_NOT_FOUND = -7;
    const FILE_TOO_BIG = -8;
    const FAILED_DOWNLOAD = -9;

    private $allowed = [];
    private $max_file_size = FALSE;
    private $dir = '/tmp';

    static public $mime_ext = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];


    function __construct()
    {

    }

    public function setAllowed($allowed = [])
    {
        $this->allowed = (array)$allowed;
    }

    public function setDir($dir)
    {
        $this->dir = rtrim((string)$dir, '/');
    }

    public function setMaxFileSize($byte)
    {
        $this->max_file_size = (int)$byte;
    }

    public function handle($file, $force = FALSE)
    {
        $output = [];
        if(is_uploaded_file($file) || $force === TRUE)
        {
            $infos = $this->getFileInfos($file);
            if ($infos['ext_safe'] === FALSE)
                return self::NO_SAFE_EXT;

            $output['infos'] = $infos;

            $check_mime = $this->checkMime($infos['mime'], $this->allowed);
            if ($check_mime === FALSE)
                return self::BAD_MIME;

            $hash = $this->getHash($file);
            if ($hash === FALSE)
                return self::NO_HASH;

            $output['hash'] = $hash;

            $check = $this->checkDir($file, $hash);
            if ($check['exist'] === FALSE)
                return self::FAILED_CREATE_DIR;

            $output['path'] = $check['path'];
            $output['name'] = 'original.' . $output['infos']['ext_safe'];
            $output['full_path'] = $output['path'] . $output['name'];

            $move = $this->move($file, $output['full_path']);
            if ($move === FALSE)
                return self::FAILED_MOVE;

            $output['move'] = $move;
            return $output;
        }
        else
        {
            return self::NO_FILE;
        }
    }

    public function handleUrl($url)
    {
        $headers = $this->urlGetHeaders($url);

        if ($headers['http_code'] !== 200)
            return self::URL_NOT_FOUND;

        if($this->max_file_size !== FALSE
            && $headers['download_content_length'] > $this->max_file_size)
            return self::FILE_TOO_BIG;

        $download = $this->urlDownload($url);
        if ($download === FALSE)
            return self::FAILED_DOWNLOAD;

        return $this->handle($download, TRUE);
    }

    public function getFileInfos($file)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file);
        $size = getimagesize($file);

        return [
            'mime' => $mime,
            'type' => $this->getTypeFromMime($mime),
            'size' => filesize($file),
            'width' => $size[0],
            'height' => $size[1],
            'ext_given' => substr_replace($file, '', 0, strrpos($file, '.') +1),
            'ext_safe' => $this->getExtFromMime($mime)
        ];
    }

    public function checkMime($mime, $allowed = NULL)
    {
        return (in_array($mime, ($allowed === NULL ? $this->allowed : $allowed)));
    }

    public function getTypeFromMime($mime)
    {
        if (strpos($mime, 'image') === 0)
            return \Custom\Media::TYPE_IMAGE;
        elseif (strpos($mime, 'video') === 0)
            return \Custom\Media::TYPE_VIDEO;
        elseif (strpos($mime, 'audio') === 0)
            return \Custom\Media::TYPE_AUDIO;
        else
            return \Custom\Media::TYPE_OTHER;

    }

    public function getExtFromMime($mime)
    {
        return isset(self::$mime_ext[$mime]) ? self::$mime_ext[$mime] : FALSE;
    }

    public function getHash($file)
    {
        return hash_file('md5', $file);
    }

    public function checkDir($file, $hash)
    {
        $dir1 = substr($hash, 0, 3);
        $dir2 = substr($hash, 3, 3);
        $dir3 = substr($hash, 6, 3);

        $path = $this->dir . '/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . $hash .'/';
        $paths = explode('/', trim($path, '/'));
        $dir = '/';
        $is_dir = is_dir($path);
        if (!$is_dir)
            mkdir($path, 0766, true);

        return [
            'existing' => $is_dir,
            'path' => $path,
            'exist' => is_dir($path)
        ];
    }

    public function move($from, $to, $safe = TRUE)
    {
        return ($safe === TRUE && is_file($to) ? TRUE : rename($from, $to));
    }

    /**
     * Get headers of url
     * @return array
     */
    public function urlGetHeaders($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        curl_exec($ch);

        $headers = curl_getinfo($ch);

        curl_close($ch);

        return $headers;
    }

    /**
     * Download from url
     * @param  string $url
     * @return mixed
     */
    function urlDownload($url)
    {
        $path = '/tmp/' . uniqid();
        $fp = fopen ($path, 'w+');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FILE, $fp);

        curl_exec($ch);
        curl_close($ch);
        # close local file
        fclose($fp);

        return (filesize($path) > 0) ? $path : FALSE;
    }
}
