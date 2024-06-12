<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use DateTime;
use Ramsey\Uuid\UuidInterface;
use DateTimeImmutable;

abstract class DomainEvent {

    public function __construct(
        public readonly UuidInterface $id,
        public readonly UuidInterface $aggregateId,
        public readonly string $type,
        public readonly DateTimeImmutable $occuredAt,
        public readonly array $data
    ) {   
    }

    public function occuredAt(): string
    {
        return $this->occuredAt->format(DateTime::ATOM);
    }

}