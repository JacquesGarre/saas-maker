<?php

declare(strict_types=1);

namespace App\Domain\Email;

use App\Domain\Shared\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class EmailSentDomainEvent extends DomainEvent
{
    public const EVENT_TYPE = 'EmailSentDomainEvent';

    private function __construct(public readonly Email $email) 
    {
        parent::__construct(
            Uuid::uuid4(),
            Uuid::uuid4(),
            self::EVENT_TYPE,
            new DateTimeImmutable(),
            $email->toArray()
        );
    }

    public static function fromEmail(Email $email): self
    {
        return new self($email);
    }
}