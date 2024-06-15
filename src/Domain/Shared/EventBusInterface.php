<?php

declare(strict_types=1);

namespace App\Domain\Shared;

interface EventBusInterface
{
    public function notify(DomainEvent $domainEvent): void;

    public function notifyAll(DomainEventCollection $domainEventCollection): void;
}
