<?php

namespace App\Model\Account\Entity\MassageForm\Price;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Location
{
    public const string HOME = 'home';

    public const string VISITOR = 'visitor';

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private string $value,
    ) {
        Assert::notEmpty($value);
        Assert::inArray($value, [self::HOME, self::VISITOR]);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
