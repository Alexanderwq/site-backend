<?php

namespace App\Tests\Unit\Model\User\Entity\User\Email;

use App\Model\User\Entity\User\Email;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $user->requestEmailChanging(
            $email = new Email('new@app.test'),
            $token = 'token',
        );

        $user->confirmEmailChanging($token);

        $this->assertEquals($email, $user->getEmail());
        $this->assertNull($user->getNewEmail());
        $this->assertNull($user->getNewEmailToken());
    }

    public function testNotRequested()
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $this->expectExceptionMessage('Changing is not requested.');
        $user->confirmEmailChanging('token');
    }

    public function testIncorrect(): void
    {
        $user = (new UserBuilder())->viaEmail()->confirmed()->build();

        $user->requestEmailChanging(
            new Email('new@app.test'),
            'token',
        );

        $this->expectExceptionMessage('Invalid token.');
        $user->confirmEmailChanging('invalid-token');
    }
}