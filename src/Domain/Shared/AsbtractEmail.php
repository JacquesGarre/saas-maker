<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use App\Domain\Shared\Exception\InvalidEmailException;

abstract class AbstractEmail {
    private function __construct(public readonly string $value) 
    {
    }

    public static function fromString(string $email): self
    {
        self::assertValid($email);
        return new self($email);
    }

    public static function assertValid(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException("$email is not a valid email");
        }
    }
}