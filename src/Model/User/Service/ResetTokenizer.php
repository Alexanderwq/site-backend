<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\User\ResetToken;
use DateInterval;
use DateTimeImmutable;
use Ramsey\Uuid\Nonstandard\Uuid;

class ResetTokenizer
{
    public function __construct(private readonly DateInterval $interval)
    {

    }

    public function generate(): ResetToken
    {
        return new ResetToken(
            Uuid::uuid4()->toString(),
            (new DateTimeImmutable())->add($this->interval),
        );
    }
}