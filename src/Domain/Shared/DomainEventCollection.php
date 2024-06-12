<?php

namespace App\Domain\Shared;

use App\Domain\Shared\DomainEvent;
use Ramsey\Collection\AbstractCollection;

final class DomainEventCollection extends AbstractCollection
{
    public function getType(): string
    {
        return DomainEvent::class;
    }
}