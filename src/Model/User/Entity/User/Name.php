<?php

namespace App\Model\User\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Name
{
    public function __construct(
        #[ORM\Column(type: 'string')]
        private string $value,
    ) {
        Assert::notEmpty($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
