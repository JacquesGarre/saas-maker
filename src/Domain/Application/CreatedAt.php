<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Exception\InvalidCreatedAtException;
use DateTimeImmutable;
use DateTime;
use Exception;

final class CreatedAt {

    private function __construct(private readonly DateTimeImmutable $value) 
    {
    }

    public static function fromString(?string $value = null): self
    {
        try {
            $datetime = new DateTimeImmutable($value);
        } catch (Exception $e) {
            throw new InvalidCreatedAtException('Invalid created at datetime format: '.$value);
        }
        return new self($datetime);
    }

    public function value(): string
    {
        return $this->value->format(DateTime::ATOM);
    }
}
