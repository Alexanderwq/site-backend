<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\User\Email;
use App\Model\User\Entity\User\ResetToken;
use RuntimeException;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

class ResetTokenSender
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly Environment   $twig,
        private readonly array         $from,
    ) {

    }

    public function send(Email $email, ResetToken $resetToken): void
    {
        $message = (new \Symfony\Component\Mime\Email())
            ->from($this->from['email'])
            ->to($email->getValue())
            ->subject('Password resetting')
            ->html($this->twig->render('mail/user/reset.html.twig', [
                'token' => $resetToken->getToken(),
            ]));

        try {
            $this->mailer->send($message);
        } catch (\Throwable $exception) {
            throw new RuntimeException('Unable to send message.');
        }
    }
}