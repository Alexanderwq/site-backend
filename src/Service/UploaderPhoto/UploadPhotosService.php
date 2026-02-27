<?php

namespace App\Service\UploaderPhoto;

use DomainException;
use Gumlet\ImageResize;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadPhotosService
{
    private const array ALLOW_EXTENSIONS = ['jpeg', 'jpg', 'png'];

    private const int|float MAX_SIZE_PHOTO = 4 * 1024 * 1024;

    private const int PHOTO_SMALL_HEIGHT = 300;

    private const int PHOTO_MEDIUM_HEIGHT = 800;

    private const string MODIFIED_PHOTOS_DIR = __DIR__ . '/../../../public/assets/uploads/photos';

    private const string ORIGIN_PHOTOS_DIR = __DIR__ . '/../../../public/assets/uploads/origin_photos';

    public function __construct()
    {
    }

    public function savePhoto(string $filename, bool $isMain, bool $isPreview): void
    {
        $ext = $this->getFileExtension($filename);
        $nameBase = pathinfo($filename, PATHINFO_FILENAME);

        $photoNameSmall = $nameBase . '_' . self::PHOTO_SMALL_HEIGHT . 'x' . self::PHOTO_SMALL_HEIGHT . '.' . $ext;
        $photoNameMedium = $nameBase . '_' . self::PHOTO_MEDIUM_HEIGHT . 'x' . self::PHOTO_MEDIUM_HEIGHT . '.' . $ext;

        $this->createResizePhoto($filename, $photoNameSmall, self::PHOTO_SMALL_HEIGHT);
        $this->createResizePhoto($filename, $photoNameMedium, self::PHOTO_MEDIUM_HEIGHT);
//        WatermarkService::add(self::MODIFIED_PHOTOS_DIR . '/' . $photoNameMedium);
    }

    public function saveOriginPhoto(UploadedFile $file): string
    {
        $this->validatePhoto($file);

        $extension = $file->getClientOriginalExtension();
        $newName = $this->generateNamePhoto($extension);
        move_uploaded_file($file, self::ORIGIN_PHOTOS_DIR . '/' . $newName);

        return $newName;
    }

    private function generateNamePhoto(string $extension): string
    {
        return Uuid::uuid4()->toString() . '.' . $extension;
    }

    private function validatePhoto(UploadedFile $photo): void
    {
        $extension = $photo->getClientOriginalExtension();
        $this->validateExtension($extension);
        $this->validateSize($photo);
    }

    private function validateExtension(string $extension): void
    {
        if (!in_array($extension, self::ALLOW_EXTENSIONS)) {
            throw new DomainException('Файлы должны быть формата: png, jpg, jpeg');
        }
    }

    private function validateSize(UploadedFile $photo): void
    {
        if ($photo->getSize() > self::MAX_SIZE_PHOTO) {
            throw new DomainException('Большой размер фотографии');
        }
    }

    private function getFileExtension(string $filename): ?string
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    private function createResizePhoto(string $photoName, string $resultPhotoName, int $height): void
    {
        $photo = new ImageResize(self::ORIGIN_PHOTOS_DIR . '/' . $photoName);
        $photo->resizeToHeight($height);
        $photo->save(self::MODIFIED_PHOTOS_DIR . '/' . $resultPhotoName);
    }
}
