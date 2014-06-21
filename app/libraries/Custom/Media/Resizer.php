<?php namespace Custom\Media;

class Resizer
{
    public function __construct()
    {
    }

    public function setSource($source)
    {
        if (!is_file($source))
            throw new Exception("[RESIZER] file doesn't exist");

        $this->source = $source;
        list ($this->width, $this->height, $type) = getimagesize($source);

        switch ($type) {
            case IMAGETYPE_GIF:
                $this->imagetype = IMAGETYPE_GIF;
                $this->image = imagecreatefromgif($source);
                break;
            case IMAGETYPE_JPEG:
                $this->imagetype = IMAGETYPE_JPEG;
                $this->image = imagecreatefromjpeg($source);
                break;
            case IMAGETYPE_PNG:
                $this->imagetype = IMAGETYPE_PNG;
                $this->image = imagecreatefrompng($source);
                break;
            default:
                throw new Exception("Image type $type not supported");
        }
    }

    public function setExtension($ext)
    {
        $this->extension = $ext;
    }

    public function filler($width, $height, $centered = TRUE)
    {
        $srcX = 0;
        $srcY = 0;
        $nW = $this->width;
        $nH = $this->height;

        $src_ratio = $this->width / $this->height;
        $trg_ratio = $width / $height;

        if ($src_ratio < $trg_ratio)
            $scale = $this->width / $width;
        else
            $scale = $this->height / $height;

        $nW = $this->width / $scale;
        $nH = $this->height / $scale;

        $srcX = ($nW - $width) / 2;
        $srcY = ($nH - $height) / 2;

        $temp = imagecreatetruecolor($width, $height);
        imagecopyresampled($temp, $this->image, 0, 0,
                           $srcX, $srcY,
                           $nW, $nH,
                           $this->width, $this->height);

        $this->saveFile($temp, $width .'x'. $height);
    }

    public function resample($width, $height, $constrain = TRUE)
    {
        if ($constrain)
        {
            if ($this->height >= $this->width) {
                $width  = round($height / $this->height * $this->width);
            } else {
                $height = round($width / $this->width * $this->height);
            }
        }

        $temp = imagecreatetruecolor($width, $height);
        imagecopyresampled($temp, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

        $this->saveFile($temp, $width .'x'. $height);
    }

    private function saveFile($file, $name)
    {
        $folder = substr($this->source, 0, strrpos($this->source, '/'));
        $destination = $folder . '/' . $name .'.' . $this->extension;

        try {
            switch ($this->imagetype)
            {
                case IMAGETYPE_GIF:
                    if (!imagegif($file, $destination)) {
                        throw new RuntimeException;
                    }
                    break;
                case IMAGETYPE_PNG:
                    if (!imagepng($file, $destination)) {
                        throw new RuntimeException;
                    }
                    break;
                case IMAGETYPE_JPEG:
                default:
                    if (!imagejpeg($file, $destination, 95)) {
                        throw new RuntimeException;
                    }
            }
        } catch (Exception $ex) {
            throw new RuntimeException('[RESIZER] can\'t save: ' . $destination);
        }
    }

    public function exec()
    {
        // Source
        $src = imagecreatefromjpeg($this->source);

        // Destination
        $dst = imagecreatetruecolor($this->width, $this->height);

        // Resize
        imagecopyresampled($dst, $src,
                           0, 0,
                           0, 0,
                           $this->width, $this->height,
                           $this->width, $this->height);

        $folder = substr($this->source, 0, strrpos($this->source, '/'));
        $destination = $folder . '/' . $this->width . 'x' . $this->height .'.' . $this->extension;

        imagejpeg($dst, $destination);
    }
}
