<?php

declare(strict_types=1);

namespace App\Domain\Shared;

trait DomainEventsTrait {

    public readonly DomainEventCollection $domainEvents;

    public function initDomainEventCollection(): void
    {
        $this->domainEvents = new DomainEventCollection();
    }

    public function notifyDomainEvent(DomainEvent $event): void
    {
        $this->domainEvents->add($event);
    }

    public function clearDomainEvents(): void
    {
        $this->domainEvents->clear();
    }
}