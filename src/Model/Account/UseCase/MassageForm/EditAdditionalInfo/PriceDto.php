<?php

namespace App\Model\Account\UseCase\MassageForm\EditAdditionalInfo;

class PriceDto
{
    public function __construct(
        public string $place,
        public int $time,
        public string $amount,
        public string $dayTime,
    ) {

    }
}
