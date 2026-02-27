<?php

namespace App\Model\Account\Entity\FavoriteMassageForm;

use Ramsey\Uuid\Nonstandard\Uuid;
use Webmozart\Assert\Assert;

class UserId
{
    public function __construct(private readonly string $value)
    {
        Assert::notEmpty($value);
    }

    public static function next(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->getValue();
    }
}
