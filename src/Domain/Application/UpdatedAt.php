<?php

declare(strict_types=1);

namespace App\Domain\Application;

use DateTimeImmutable;
use DateTime;

final class UpdatedAt {

    private function __construct(private readonly DateTimeImmutable $value) 
    {
    }

    public static function now(): self
    {
        $datetime = new DateTimeImmutable();
        return new self($datetime);
    }

    public function value(): string
    {
        return $this->value->format(DateTime::ATOM);
    }
}
