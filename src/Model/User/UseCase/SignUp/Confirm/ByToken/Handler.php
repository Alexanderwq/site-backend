<?php

namespace App\Model\User\UseCase\SignUp\Confirm\ByToken;

use App\Model\Flusher;
use App\Model\User\Entity\User\UserRepository;
use DomainException;

class Handler
{
    public function __construct(private readonly UserRepository $users, private readonly Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findByConfirmToken($command->token)) {
            throw new DomainException('Invalid token.');
        }

        $user->confirmSignUp();

        $this->flusher->flush();
    }
}