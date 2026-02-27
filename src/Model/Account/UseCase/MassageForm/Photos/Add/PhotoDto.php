<?php

namespace App\Model\Account\UseCase\MassageForm\Photos\Add;

class PhotoDto
{
    public function __construct(
        public string $filename,
        public bool $isMain,
        public bool $isPreview,
    ) {

    }
}
