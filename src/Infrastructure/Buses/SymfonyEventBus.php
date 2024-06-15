<?php

declare(strict_types=1);

namespace App\Infrastructure\Buses;

use App\Domain\Shared\DomainEvent;
use App\Domain\Shared\DomainEventCollection;
use App\Domain\Shared\EventBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class SymfonyEventBus implements EventBusInterface {

    public function __construct(private readonly MessageBusInterface $eventBus)
    {
    }

    /**
     * @throws Throwable
     */
    public function notify(DomainEvent $domainEvent): void
    {
        try {
            $this->eventBus->dispatch($domainEvent);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        }
    }

    public function notifyAll(DomainEventCollection $domainEventCollection): void
    {
        foreach ($domainEventCollection->getIterator() as $domainEvent) {
            $this->notify($domainEvent);
        }
    }
}