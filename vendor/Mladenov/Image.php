<?php
namespace Mladenov;

class Image
{
    static function createThumbnail($pathToFile, $fileType, $width, $height) {
        switch ($fileType) {
            case 'jpg':
            case 'jpeg':
                $im = imagecreatefromjpeg($pathToFile);
            break;
            case 'gif':
                $im = imagecreatefromgif($pathToFile);
            break;
            case 'png':
                $im = imagecreatefrompng($pathToFile);
            break;
            default:
                throw new \Exception('Unknown file type' . $fileType);
            break;
        }

        $ox = imagesx($im);
        $oy = imagesy($im);

        $nx = $width;
        $ny = $height;

        $nm = imagecreatetruecolor($nx, $ny);

        imagecopyresized($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);

        ob_start();
        switch ($fileType) {
            case 'jpg':
            case 'jpeg':
                imagejpeg($nm);
            break;
            case 'gif':
                imagegif($nm);
            break;
            case 'png':
                imagepng($nm);
            break;
        }
        $contents =  ob_get_contents();
        ob_end_clean();

        return $contents;
    }
}