<?php

namespace App\Model\Account\UseCase\MassageForm\EditAdditionalInfo;

class MassageAdditionalOptionDto
{
    public function __construct(
        public int $option,
        public int $price,
        public string $description,
    ) {

    }
}
