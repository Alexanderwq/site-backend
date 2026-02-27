<?php

namespace App\Tests\Unit\Model\User\Entity\User\Reset;

use App\Model\User\Entity\User\ResetToken;
use App\Tests\Builder\User\UserBuilder;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testSuccess(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = new UserBuilder()->viaEmail()->confirmed()->build();

        $user->requestPasswordReset($token, $now);

        self::assertNotNull($user->getResetToken());
    }

    public function testAlready(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $user->requestPasswordReset($token, $now);

        self::expectExceptionMessage('Resetting token already requested.');
        $user->requestPasswordReset($token, $now);
    }

    public function testExpired(): void
    {
        $now = new DateTimeImmutable();

        $user = new UserBuilder()->viaEmail()->confirmed()->build();

        $token = new ResetToken('token', $now->modify('+1 day'));
        $user->requestPasswordReset($token, $now);

        self::assertEquals($token, $user->getResetToken());

        $token2 = new ResetToken('token', $now->modify('+3 day'));
        $user->requestPasswordReset($token2, $now->modify('+2 day'));

        self::assertEquals($token2, $user->getResetToken());
    }

    public function testNotConfirmed(): void
    {
        $now = new DateTimeImmutable();
        $token = new ResetToken('token', $now->modify('+1 day'));

        $user = new UserBuilder()->viaEmail()->build();

        self::expectExceptionMessage('User is not active.');
        $user->requestPasswordReset($token, $now);
    }
}
