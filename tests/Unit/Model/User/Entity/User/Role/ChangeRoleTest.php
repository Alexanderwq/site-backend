<?php

namespace App\Tests\Unit\Model\User\Entity\User\Role;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Role;
use App\Tests\Builder\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class ChangeRoleTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = (new UserBuilder())
            ->viaEmail(
                new Email('test@test.ru'),
                'hash',
                'token',
            )
            ->build();

        $user->changeRole(Role::admin());

        self::assertTrue($user->getRole()->isAdmin());
        self::assertFalse($user->getRole()->isUser());
    }

    public function testAlready(): void
    {
        $user = (new UserBuilder())
            ->viaEmail(
                new Email('test@test.ru')
            )
            ->build();

        self::expectExceptionMessage('Role already assigned.');
        $user->changeRole(Role::user());
    }
}