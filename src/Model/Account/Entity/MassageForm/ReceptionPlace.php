<?php

namespace App\Model\Account\Entity\MassageForm;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

#[ORM\Embeddable]
readonly class ReceptionPlace
{
    private const string ALL = 'all';

    private const string HOME = 'home';

    private const string VISITOR = 'visitor';

    public function __construct(
        #[ORM\Column(type: Types::INTEGER)]
        private string $value,
    ) {
        Assert::notEmpty($value);
        Assert::inArray($this->value, [self::ALL, self::HOME, self::VISITOR]);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
