<?php

namespace App\Model\User\UseCase\Reset\Request;

use App\Model\Flusher;
use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\UserRepository;
use App\Model\User\Service\ResetTokenizer;
use App\Model\User\Service\ResetTokenSender;
use DateTimeImmutable;
use Doctrine\ORM\EntityNotFoundException;

class Handler
{
    public function __construct(
        private readonly UserRepository   $users,
        private readonly ResetTokenizer   $tokenizer,
        private readonly Flusher          $flusher,
        private readonly ResetTokenSender $resetTokenSender,
    ) {

    }

    public function handle(Command $command): void
    {
        try {
            $user = $this->users->getByEmail(new Email($command->email));
        } catch (EntityNotFoundException $exception) {
            throw new \DomainException($exception->getMessage());
        }

        $user->requestPasswordReset(
            $this->tokenizer->generate(),
            new DateTimeImmutable(),
        );

        $this->flusher->flush();

        $this->resetTokenSender->send($user->getEmail(), $user->getResetToken());
    }
}