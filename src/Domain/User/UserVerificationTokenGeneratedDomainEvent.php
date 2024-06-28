<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class UserVerificationTokenGeneratedDomainEvent extends DomainEvent
{
    public const EVENT_TYPE = 'UserVerificationTokenGeneratedDomainEvent';

    private function __construct(public readonly User $user) 
    {
        parent::__construct(
            Uuid::uuid4(),
            $user->id()->value,
            self::EVENT_TYPE,
            new DateTimeImmutable(),
            $user->toArray()
        );
    }

    // TODO: Unit test this
    public static function fromUser(User $user): self
    {
        return new self($user);
    }
}