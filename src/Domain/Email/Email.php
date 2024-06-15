<?php

declare(strict_types=1);

namespace App\Domain\Email;

use App\Domain\Email\CcCollection;
use App\Domain\Email\BccCollection;
use App\Domain\Shared\DomainEventsTrait;
use App\Domain\Shared\Id;
use App\Domain\Email\ToCollection;
use App\Domain\Email\EmailSentDomainEvent;

abstract class Email {

    use DomainEventsTrait;

    public function __construct(
        public readonly Id $id,
        public readonly EmailSenderInterface $sender,
        public readonly From $from,
        public readonly ToCollection $toCollection,
        public readonly Subject $subject,
        public readonly Html $html,
        public readonly ?CcCollection $ccCollection = null,
        public readonly ?BccCollection $bccCollection = null,
    ) {
        $this->initDomainEventCollection();
    }

    public function send(): void
    {
        $this->sender->sendEmail($this);
        $this->notifyDomainEvent(EmailSentDomainEvent::fromEmail($this));
    }

    public function toArray(): array
    {
        return [
            'from' => $this->from->value,
            'to' => $this->toCollection->toArray(),
            'subject' => $this->subject->value,
            'html' => $this->html->value,
            'cc' => $this->ccCollection?->toArray(),
            'bcc' => $this->bccCollection?->toArray()
        ];
    }
}