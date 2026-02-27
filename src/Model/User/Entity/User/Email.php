<?php

namespace App\Model\User\Entity\User;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Email
{
    public function __construct(private string $value)
    {
        Assert::notEmpty($value);

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email');
        }

        $this->value = mb_strtolower($value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(Email $email): bool
    {
        return $this->value === $email->getValue();
    }
}