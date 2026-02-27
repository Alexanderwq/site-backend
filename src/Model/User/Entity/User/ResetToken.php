<?php

namespace App\Model\User\Entity\User;

use DateTimeImmutable;
use Webmozart\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class ResetToken
{
    public function __construct(
        #[ORM\Column(type: 'string', nullable: true)]
        private readonly ?string $token = null,
        #[ORM\Column(type: 'datetime_immutable', nullable: true)]
        private readonly ?DateTimeImmutable $expires = null,
    ) {
        Assert::notEmpty($this->token);
    }

    public function isExpiredTo(DateTimeImmutable $dateTimeImmutable): bool
    {
        return $this->expires <= $dateTimeImmutable;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }
}