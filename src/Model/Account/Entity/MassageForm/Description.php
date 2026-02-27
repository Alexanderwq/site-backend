<?php

namespace App\Model\Account\Entity\MassageForm;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Description
{
    public function __construct(
        #[ORM\Column(type: 'string')]
        private string $value,
    ) {
        Assert::notEmpty($value);
        Assert::maxLength($value, 2000);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
