<?php

namespace App\Model\Account\Entity\MassageForm\Price;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class DayTime
{
    public const string DAY = 'day';

    public const string NIGHT = 'night';

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private string $value,
    ) {
        Assert::notEmpty($value);
        Assert::inArray($value, [self::DAY, self::NIGHT]);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
