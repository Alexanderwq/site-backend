<?php

namespace App\Model\Account\Entity\MassageForm\Event;

class MassageFormPhotoAdded
{
    public function __construct(
        public string $filename,
        public bool $isPreview,
        public bool $isMain,
    ) {
    }
}
