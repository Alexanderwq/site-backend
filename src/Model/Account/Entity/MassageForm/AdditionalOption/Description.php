<?php

namespace App\Model\Account\Entity\MassageForm\AdditionalOption;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class Description
{
    public function __construct(
        #[ORM\Column(type: 'string', nullable: true)]
        private ?string $value,
    ) {
        if ($value !== null) {
            Assert::notEmpty($value);
            Assert::maxLength($value, 500);
        }
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
