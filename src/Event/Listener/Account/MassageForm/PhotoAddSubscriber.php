<?php

namespace App\Event\Listener\Account\MassageForm;

use App\Model\Account\Entity\MassageForm\Event\MassageFormPhotoAdded;
use App\Service\UploaderPhoto\UploadPhotosService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PhotoAddSubscriber implements EventSubscriberInterface
{
    public function __construct(private UploadPhotosService $uploader)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MassageFormPhotoAdded::class => 'onPhotoAdded',
        ];
    }

    public function onPhotoAdded(MassageFormPhotoAdded $event): void
    {
        $this->uploader->savePhoto($event->filename, $event->isMain, $event->isPreview);
    }
}
