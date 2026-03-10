<?php

namespace App\Model\Account\Entity\MassageForm;

use Doctrine\DBAL\Types\Types;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
readonly class Coords
{
    public function __construct(
        #[ORM\Column(type: Types::FLOAT, nullable: true)]
        private float $lat,
        #[ORM\Column(type: Types::FLOAT, nullable: true)]
        private float $long,
    ) {
        Assert::notEmpty($lat);
        Assert::notEmpty($long);
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLong(): float
    {
        return $this->long;
    }
}

