<?php

namespace App\Security;

use App\ReadModel\User\UserFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface
{
    public function __construct(private UserFetcher $users)
    {

    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof UserIdentity) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user = $this->users->findForAuth($user->getUserIdentifier());

        if (!$user) {
            throw new UserNotFoundException();
        }

        return new UserIdentity(
            $user->id,
            $user->email,
            $user->password_hash,
            $user->name,
            $user->role,
            $user->status,
        );
    }

    public function supportsClass(string $class): bool
    {
        return $class === UserIdentity::class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = $this->users->findForAuth($identifier);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return new UserIdentity(
            $user->id,
            $user->email,
            $user->password_hash,
            $user->name,
            $user->role,
            $user->status,
        );
    }
}
