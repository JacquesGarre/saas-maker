<?php

declare(strict_types=1);

namespace App\Infrastructure\Buses;

use App\Domain\Shared\QueryBusInterface;
use App\Domain\Shared\QueryInterface;
use App\Domain\Shared\QueryResultInterface;
use App\Infrastructure\Buses\Exception\QueryNotHandledException;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final class SymfonyQueryBus implements QueryBusInterface {

    public function __construct(private readonly MessageBusInterface $queryBus)
    {
    }

    /**
     * @throws Throwable
     */
    public function dispatch(QueryInterface $query): QueryResultInterface
    {
        try {
            $envelope = $this->queryBus->dispatch($query);
            $handledStamp = $envelope->last(HandledStamp::class);
            if (null === $handledStamp) {
                throw new QueryNotHandledException("Query was not handled properly");
            }
            return $handledStamp->getResult();
        } catch (HandlerFailedException $exception) {
            throw $exception->getPrevious();
        }
    }
}