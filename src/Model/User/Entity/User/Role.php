<?php

namespace App\Model\User\Entity\User;

use Webmozart\Assert\Assert;

class Role
{
    private const USER = 'ROLE_USER';

    private const ADMIN = 'ROLE_ADMIN';

    public function __construct(private string $name)
    {
        Assert::oneOf($name, [self::USER, self::ADMIN]);
    }

    public static function user(): self
    {
        return new self(self::USER);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function isUser(): bool
    {
        return $this->name === self::USER;
    }

    public function isAdmin(): bool
    {
        return $this->name === self::ADMIN;
    }

    public function isEqual(Role $role): bool
    {
        return $this->getName() === $role->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
