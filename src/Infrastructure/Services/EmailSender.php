<?php

namespace App\Infrastructure\Service;

use App\Domain\Email\EmailSenderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

final class EmailSender implements EmailSenderInterface
{
    public function __construct(
        private readonly MailerInterface $mailer
    ) {
    }

    public function sendEmail(
        string $from, 
        array $to, 
        array $cc, 
        array $bcc, 
        string $subject, 
        string $body, 
        array $attachments = []
    ): void {
        $email = (new Email())
            ->from($from)
            ->cc(...$cc)
            ->bcc(...$bcc)
            ->to(...$to)
            ->subject($subject)
            ->text(strip_tags($body))
            ->html($body);
        foreach ($attachments as $attachment) {
            $email->attachFromPath($attachment);
        }
        $this->mailer->send($email);
    }
}