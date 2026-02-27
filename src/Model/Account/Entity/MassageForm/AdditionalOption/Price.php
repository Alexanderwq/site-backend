<?php

namespace App\Model\Account\Entity\MassageForm\AdditionalOption;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Price
{
    public const int MIN = 500;

    public const int MAX = 500000;

    public function __construct(
        #[ORM\Column(type: Types::INTEGER, nullable: true)]
        private ?int $value,
    ) {
        if ($value !== null) {
            Assert::notEmpty($value);
            Assert::range($value, self::MIN, self::MAX);
        }
    }

    public function getValue(): ?int
    {
        return $this->value;
    }
}
