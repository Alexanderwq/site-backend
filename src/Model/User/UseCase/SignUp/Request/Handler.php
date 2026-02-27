<?php

namespace App\Model\User\UseCase\SignUp\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\Id;
use App\Model\User\Entity\User\Name;
use App\Model\User\Entity\User\User;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\SignUpConfirmTokenizer;
use App\Model\User\Service\SignUpConfirmTokenSender;
use App\Model\User\Service\PasswordHasher;
use DateTimeImmutable;
use DomainException;

class Handler
{
    public function __construct(
        private Flusher                $em,
        private UserRepository         $users,
        private PasswordHasher         $passwordHasher,
        private SignUpConfirmTokenizer $tokenizer,
        private SignUpConfirmTokenSender     $sender,
    ) {

    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('Email already exists.');
        }

        $user = User::signUpByEmail(
            Id::next(),
            new DateTimeImmutable(),
            new Name($command->name),
            $email,
            $this->passwordHasher->hash($command->password),
            $token = $this->tokenizer->generate(),
        );

        $this->users->add($user);

        $this->sender->send($email, $token);

        $this->em->flush();
    }
}
