<?php

namespace App\Tests\Unit\Model\Account\Entity\MassageForm;

use App\Model\Account\Entity\MassageForm\Photo\Id;
use App\Model\Account\UseCase\MassageForm\Photos\Add\PhotoDto;
use App\Tests\Builder\Account\MassageForm\MassageFormBuilder;
use PHPUnit\Framework\TestCase;

final class ChangePhotosTest extends TestCase
{
    public function testChangePhotos(): void
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('photo1.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $form->changePhotos($photos);

        self::assertCount(5, $form->getPhotos());
    }

    public function testMultipleChangePhotos(): void
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('photo1.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $form->changePhotos($photos);

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('photo1.png', false, false),
            new PhotoDto('photo2.png', false, false),
        ];

        $form->changePhotos($photos);

        self::assertCount(8, $form->getPhotos());
    }

    public function testAddAndRemove(): void
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $form->changePhotos($photos);

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', false, false),
            new PhotoDto('preview.jpg', false, false),
        ];

        $photoId = $form->getPhotos()[0]->getId()->getValue();

        $form->changePhotos($photos, [$photoId]);

        $photos = $form->getPhotos();

        self::assertCount(7, $photos);
    }

    public function testReplacePreview()
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $form->changePhotos($photos);

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', false, false),
            new PhotoDto($filename = 'preview.jpg', false, true),
        ];

        $oldPreviewId = $form->getPreviewPhoto()?->getId()->getValue();
        $form->changePhotos($photos, [$oldPreviewId]);

        $photos = $form->getPhotos();

        $actualPreview = $form->getPreviewPhoto();

        self::assertCount(7, $photos);
        self::assertEquals($filename, $actualPreview->getName());
    }

    public function testReplaceMain()
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $form->changePhotos($photos);

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto($newMainPhotoName = 'main2.jpg', true, false),
            new PhotoDto('preview.jpg', false, false),
        ];

        $oldMainPhotoId = $form->getMainPhoto()?->getId()->getValue();

        $form->changePhotos($photos, [$oldMainPhotoId]);

        $photos = $form->getPhotos();

        $actualMainPhoto = $form->getMainPhoto()->getName();

        self::assertCount(7, $photos);
        self::assertEquals($actualMainPhoto, $newMainPhotoName);
    }

    public function testWithoutPreview(): void
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('photo1.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, false),
        ];

        self::expectExceptionMessage('Нет превью фотографии');
        $form->changePhotos($photos);
    }

    public function testWithoutMain(): void
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('photo1.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('main.jpg', false, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        self::expectExceptionMessage('Нет фотографии обложки');
        $form->changePhotos($photos);
    }

    public function testWithLessPhotos(): void
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        self::expectExceptionMessage('Слишком мало фотографий');
        $form->changePhotos($photos);
    }

    public function testReplacePreviewWithoutRemove()
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $form->changePhotos($photos);

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', false, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        self::expectExceptionMessage('Нужно удалить старое фото превью');
        $form->changePhotos($photos);
    }

    public function testReplaceMainWithoutRemove()
    {
        $form = new MassageFormBuilder()->build();

        $photos = [
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ];

        $form->changePhotos($photos);

        $photos = [
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, false),
        ];

        self::expectExceptionMessage('Нужно удалить старое фото обложки');
        $form->changePhotos($photos);
    }

    public function testRemoveMainWithoutNewMainThrows(): void
    {
        $form = new MassageFormBuilder()->build();
        $form->changePhotos([
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ]);

        $mainId = $form->getMainPhoto()->getId()->getValue();

        self::expectExceptionMessage('Нет фотографии обложки');

        $form->changePhotos(
            [new PhotoDto('photo.png', false, false)],
            [$mainId]
        );
    }

    public function testRemoveUnknownPhotoIdIsIgnored(): void
    {
        $form = new MassageFormBuilder()->build();
        $form->changePhotos([
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ]);

        $form->changePhotos(
            [new PhotoDto('photo4.png', false, false)],
            ['non-existing-id']
        );

        self::assertCount(6, $form->getPhotos());
    }

    public function testChangePhotosIsInvariantSafe(): void
    {
        $form = new MassageFormBuilder()->build();

        $form->changePhotos([
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ]);

        $form->changePhotos([]);

        self::assertNotNull($form->getMainPhoto());
        self::assertNotNull($form->getPreviewPhoto());
    }

    public function testReplaceMainAndPreviewInOneCall(): void
    {
        $form = new MassageFormBuilder()->build();
        $form->changePhotos([
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ]);

        $form->changePhotos(
            [
                new PhotoDto('new-main.jpg', true, false),
                new PhotoDto('new-preview.jpg', false, true),
                new PhotoDto('photo.png', false, false),
            ],
            [
                $form->getMainPhoto()->getId()->getValue(),
                $form->getPreviewPhoto()->getId()->getValue(),
            ]
        );

        self::assertEquals('new-main.jpg', $form->getMainPhoto()->getName());
    }

    public function testTwoMainInNewPhotosThrows(): void
    {
        self::expectExceptionMessage('Нужно удалить старое фото обложки');

        $form = new MassageFormBuilder()->build();
        $form->changePhotos([
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ]);


        $form->changePhotos([
            new PhotoDto('main1.jpg', true, false),
            new PhotoDto('main2.jpg', true, false),
        ]);
    }

    public function testTwoPreviewInNewPhotosThrows(): void
    {
        self::expectExceptionMessage('Нужно удалить старое фото превью');

        $form = new MassageFormBuilder()->build();
        $form->changePhotos([
            new PhotoDto('photo.png', false, false),
            new PhotoDto('photo2.png', false, false),
            new PhotoDto('photo3.png', false, false),
            new PhotoDto('main.jpg', true, false),
            new PhotoDto('preview.jpg', false, true),
        ]);


        $form->changePhotos([
            new PhotoDto('main1.jpg', false, true),
            new PhotoDto('main2.jpg', false, true),
        ]);
    }
}
