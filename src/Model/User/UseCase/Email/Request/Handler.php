<?php

namespace App\Model\User\UseCase\Email\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\NewEmailConfirmTokenizer;
use App\Model\User\Service\NewEmailConfirmTokenSender;
use DomainException;

class Handler
{
    public function __construct(
        private readonly UserRepository             $users,
        private readonly Flusher                    $flusher,
        private readonly NewEmailConfirmTokenizer   $tokenizer,
        private readonly NewEmailConfirmTokenSender $sender,
    ) {
    }

    public function handle(Command $command): void
    {
        $user = $this->users->get(new Id($command->id));

        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('Email already in use.');
        }

        $user->requestEmailChanging(
            $email,
            $token = $this->tokenizer->generate(),
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}