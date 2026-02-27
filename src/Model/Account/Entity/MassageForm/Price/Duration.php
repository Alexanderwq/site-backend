<?php

namespace App\Model\Account\Entity\MassageForm\Price;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Duration
{
    public const int HOUR = 1;

    public const int TWO_HOURS = 2;

    public function __construct(
        #[ORM\Column(type: Types::INTEGER)]
        private int $value,
    ) {
        Assert::notEmpty($value);
        Assert::inArray($value, [self::HOUR, self::TWO_HOURS]);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
