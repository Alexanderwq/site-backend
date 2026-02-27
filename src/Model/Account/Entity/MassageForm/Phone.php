<?php

namespace App\Model\Account\Entity\MassageForm;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Phone
{
    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private string $value,
    ) {
        Assert::notEmpty($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
