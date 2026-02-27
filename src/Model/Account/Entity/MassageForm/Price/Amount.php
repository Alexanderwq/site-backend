<?php

namespace App\Model\Account\Entity\MassageForm\Price;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Amount
{
    public const int MIN = 500;

    public const int MAX = 500000;

    public function __construct(
        #[ORM\Column(type: Types::INTEGER)]
        private int $value,
    ) {
        Assert::notEmpty($value);
        Assert::range($value, self::MIN, self::MAX);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
