<?php

namespace App\Infrastructure\Service;

use App\Domain\Email\EmailSenderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;
use App\Domain\Email\Email;

final class EmailSender implements EmailSenderInterface
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    public function sendEmail(Email $email): void {
        $symfonyEmail = (new SymfonyEmail())
            ->from($email->from->value)
            ->subject($email->subject->value)
            ->text($email->html->toText())
            ->html($email->html->value)
            ->to(...$email->toCollection->toArray())
            ->bcc(...$email->bccCollection->toArray())
            ->cc(...$email->ccCollection->toArray());
        $this->mailer->send($symfonyEmail);
    }
}