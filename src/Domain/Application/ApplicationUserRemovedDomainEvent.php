<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Shared\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class ApplicationUserRemovedDomainEvent extends DomainEvent
{
    public const EVENT_TYPE = 'ApplicationUserRemovedDomainEvent';

    private function __construct(public readonly ApplicationUser $applicationUser) 
    {
        parent::__construct(
            Uuid::uuid4(),
            $applicationUser->application->id->value,
            self::EVENT_TYPE,
            new DateTimeImmutable(),
            $applicationUser->toArray()
        );
    }

    public static function fromApplicationUser(ApplicationUser $applicationUser): self
    {
        return new self($applicationUser);
    }
}