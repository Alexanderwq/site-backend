<?php

namespace App\Model\Account\Entity\MassageForm;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Age
{
    public function __construct(
        #[ORM\Column(type: Types::INTEGER)]
        private int $value,
    ) {
        Assert::notEmpty($value);
        Assert::range($value, 18, 70);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
