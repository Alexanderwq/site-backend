<?php

namespace App\Service\WatermarkService;

use Exception;
use GdImage;

class WatermarkService
{
    private static string $WATERMARK_PATH = __DIR__ . '../../../public/assets/images/watermark.png';

    /**
     * @throws Exception
     */
    private static function createImageFromTarget(string $target): GdImage|false
    {
        $info = getimagesize($target);

        if ($info === false) {
            throw new Exception('Невозможно определить тип изображения ' . $target);
        }

        return match ($info['mime']) {
            ImageTypes::png->value => imagecreatefrompng($target),
            ImageTypes::jpg->value, ImageTypes::jpeg->value => imagecreatefromjpeg($target),
            default => throw new Exception('Не поддерживаемый формат изображения ' . $target)
        };
    }

    /**
     * @throws Exception
     */
    private static function outputImage(GdImage $image, $file): void
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        match ($extension) {
            ImageTypes::png->name => imagepng($image, $file),
            ImageTypes::jpg->name, ImageTypes::jpeg->name => imagejpeg($image, $file),
            default => throw new Exception('Не валидный формат изображения ' . $file)
        };
    }

    /**
     * @throws Exception
     */
    public static function add(string $target): void
    {
        $img = self::createImageFromTarget($target);
        if ($img === false) {
            throw new Exception('Произошла ошибка при добавлении ватермарки - ' . $target);
        }
        $watermark = imagecreatefrompng(self::$WATERMARK_PATH);
        imagealphablending($watermark, false);
        imagesavealpha($watermark, true);
        $img_w = imagesx($img);
        $img_h = imagesy($img);
        $wtrmrk_w = imagesx($watermark);
        $wtrmrk_h = imagesy($watermark);
        $dst_x = (int) (($img_w / 2) - ($wtrmrk_w / 2));
        $dst_y = (int) (($img_h / 2) - ($wtrmrk_h / 2));
        imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
        self::outputImage($img, $target);
        imagedestroy($img);
        imagedestroy($watermark);
    }
}
