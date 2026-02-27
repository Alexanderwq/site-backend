<?php

namespace App\Model\User\Service;
use App\Model\User\Entity\User\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;
use Twig\Environment;
use RuntimeException;

class NewEmailConfirmTokenSender
{
    public function __construct(private readonly MailerInterface $mailer, private readonly Environment $twig, private readonly array $from)
    {
    }

    public function send(Email $email, string $token): void
    {
        $message = (new SymfonyEmail())
            ->from($this->from['email'])
            ->to($email->getValue())
            ->subject('Change email')
            ->html($this->twig->render('mail/user/email.html.twig', [
                'token' => $token,
            ]));

        try {
            $this->mailer->send($message);
        } catch (\Throwable $e) {
            throw new RuntimeException('Unable to send message: ' . $e->getMessage(), 0, $e);
        }
    }
}
