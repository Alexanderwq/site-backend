<?php

namespace App\Model\User\UseCase\Role;

use App\Model\User\Entity\User\User;
use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    #[Assert\NotBlank]
    public string $id;

    #[Assert\NotBlank]
    public string $role;

    public static function fromUser(User $user): self
    {
        $command = new self();
        $command->id = $user->getId()->getValue();
        $command->role = $user->getRole()->getName();
        return $command;
    }
}