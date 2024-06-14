<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use DateTimeImmutable;
use DateTime;
use DateTimeInterface;

final class CreatedAt {

    private function __construct(public readonly DateTimeImmutable $value)
    {
    }

    public static function now(): self
    {
        $datetime = new DateTimeImmutable();
        return new self($datetime);
    }

    public function value(): string
    {
        return $this->value->format(DateTimeInterface::ATOM);
    }
}
