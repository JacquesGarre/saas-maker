<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Shared\DomainEvent;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class ApplicationCreatedDomainEvent extends DomainEvent
{
    public const EVENT_TYPE = 'ApplicationCreatedDomainEvent';

    private function __construct(public readonly Application $application) 
    {
        parent::__construct(
            Uuid::uuid4(),
            $application->id->value,
            self::EVENT_TYPE,
            new DateTimeImmutable(),
            $application->toArray()
        );
    }

    public static function fromApplication(Application $application): self
    {
        return new self($application);
    }
}