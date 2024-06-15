<?php

declare(strict_types=1);

namespace App\Infrastructure\Buses;

use App\Domain\Shared\CommandBusInterface;
use App\Domain\Shared\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class SymfonyCommandBus implements CommandBusInterface {

    public function __construct(private readonly MessageBusInterface $commandBus)
    {
    }

    /**
     * @throws Throwable
     */
    public function dispatch(CommandInterface $command): void
    {
        try {
            $this->commandBus->dispatch($command);
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        }
    }
}