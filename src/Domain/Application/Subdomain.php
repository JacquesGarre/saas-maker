<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Application\Exception\InvalidSubdomainException;

final class Subdomain {

    public const VALID_SLUG_REGEX = '/^[a-z0-9]+(?:-[a-z0-9]+)*$/';

    private function __construct(public readonly string $value) 
    {
    }

    public static function fromString(string $value): self
    {
        self::assertValid($value);
        return new self($value);
    }

    public static function assertValid(string $value): void
    {
        if (!preg_match(self::VALID_SLUG_REGEX, $value)) {
            throw new InvalidSubdomainException("$value is not a valid subdomain, must match the following regex : ".self::VALID_SLUG_REGEX);
        }
    }

}
