<?php

namespace App\Tests\Unit\Model\User\Entity\User\SignUp;

use App\Model\User\Entity\User\Email;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        self::assertTrue($user->isActive());
        self::assertFalse($user->isWait());

        self::assertNull($user->getConfirmToken());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())->viaEmail(
            new Email('test@test.ru'),
            'hash',
            $token = 'token',
        )->confirmed()->build();

        self::expectExceptionMessage('User is already confirmed.');
        $user->confirmSignUp();

        self::assertNull($token);
    }
}