<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class UserLoggedInDomainEvent extends DomainEvent
{
    public const EVENT_TYPE = 'UserLoggedInDomainEvent';

    private function __construct(public readonly User $user) 
    {
        parent::__construct(
            Uuid::uuid4(),
            $user->id->value,
            self::EVENT_TYPE,
            new DateTimeImmutable(),
            $user->toArray()
        );
    }

    public static function fromUser(User $user): self
    {
        return new self($user);
    }
}