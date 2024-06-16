<?php

namespace App\Infrastructure\Services;

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
        $to = $email->toCollection->toArray();
        $bcc = $email->bccCollection?->toArray();
        $cc = $email->ccCollection?->toArray();
        $symfonyEmail = (new SymfonyEmail())
            ->from($email->from->value)
            ->subject($email->subject->value)
            ->text($email->html->toText())
            ->html($email->html->value)
            ->to(...$to);
        if ($bcc) {
            $symfonyEmail->bcc(...$bcc);
        }
        if ($cc) {
            $symfonyEmail->cc(...$cc);
        }
        $this->mailer->send($symfonyEmail);
    }
}